<?php

namespace core\aggregates\services;

use core\aggregates\account;
use core\aggregates\collection;
use core\aggregates\payment;
use core\aggregates\tenant;
use core\aggregates\transaction;
use core\exceptions\ServiceException;
use infrastructure\database\specifications\Repositories\PaymentRepository;
use infrastructure\database\specifications\Repositories\TenantRepository;
use web\libs\Request;

final class PaymentService extends _BaseService {

    public PaymentRepository $repository;

    private PropertyService $propertyService ; 

    private TenantRepository $tenantRepository;

    public function __construct()
    {
      parent :: __construct();

      $this->repository = new PaymentRepository();

      $this->propertyService = new PropertyService();

      $this->tenantRepository = new TenantRepository();
    }


    public function recordPayment(Request $request , account $account){
    
        try{
            $payment = new payment();
            $payment->amount_paid =  floatval($request->body->amount_paid);
            $payment->reason = $request->body->reason;
            $payment->receipt = isset($request->body->receipt)? $request->body->receipt : '';
            $payment->payment_mode = '_cash_';
            $payment->account_id = $account->id;
            $payment->date_payment = $request->body->payment_date;
            if(in_array($request->body->reason,["RENT",'SECURITY_MONTH','SERVICE_FEE','BILL'])) throw new ServiceException("unknown payment reason");
            $payment->model_id = $request->body->tenant_id;
            $payment->model = tenant::class;

            // perform the cash transaction
            $data = array(
                '_cash_' =>true,
                '_wallet_' => false,
                'amount' => $payment->amount_paid,
                'reason' => $payment->reason,
                'type' => ($payment->reason == "SECURITY_MONTH")? "SECURITY" : "CREDIT",
                'transaction_date'=> $payment->date_payment,
                'approved' => true,
                'approved_by'=> $account->id
            );

            $payment->transaction_ref = $this->recordTransaction($data,$account);
            $this->repository->Add($payment);
            $this->repository->saveChanges();
            // reverse on failure
            if(!$this->repository->last_insert_id()) $this->reverseTransaction($payment->transaction_ref , $account); throw new ServiceException("Could not record transaction for this payment try again");
            if($payment->reason == "SECURITY_MONTH") return true;
            if($payment->reason == 'RENT' || $payment->reason == 'SERVICE_FEE' || $payment->reason == 'BILL' ){
                // update the  rent paid
                $rent_due = $this->repository->getTenantDueRef($request->body->rent_id);
                
                $rent_due->paid_amount = $rent_due->paid_amount + $payment->amount_paid;

                if($rent_due->paid_amount == $rent_due->amount) $rent_due->is_paid = true;

                $this->repository->Add($rent_due);

                $this->repository->saveChanges();

                /***
                 * 
                 * on rent payment notification.
                 * */ 

                 $tenant = $this->tenantRepository->findById($rent_due->tenant_id);

                $this->notificationService->sendNotification('on_rent_payment',array(
                    "template_data" => array(
                        'tenant_name' => $tenant->names,
                        'amount_paid' => $payment->amount_paid,
                        'currency' => 'UGX',
                        'timestamps' => DateService::current_timestamp()

                    ),
                    "_config_" => array(
                        'recipient_account_type' => account::class,
                        'recipient_id' => $account->id,
                    )));

                return true;
            }
            
        }
        catch(ServiceException $e){
            return $e->infoMessage();
        }

    }


    public function recordTransaction($data , account $account){
        try{
        if( ($data['_wallet'] == true && $data['_cash_'] == true) || ($data['_wallet'] == false && $data['_cash_'] == false)) throw new ServiceException(ServiceException::UNKNOWN_TRANSACTION_MODE);
        $wallet = $this->accountRepository->getWallet($account);
        if(is_null($wallet)) throw new ServiceException(ServiceException::UNKNOWN_WALLET_REFERENCE);
        $transaction = new transaction();
        $transaction->amount = $data['amount'];
        $transaction->transaction_date = ['transaction_date'];
        $transaction->reference_no = $this->generateTransactionReferenceNumber($account , $data['reason']);
        $transaction->reason = $data['reason'];
        $transaction->transaction_type = $data['type'];
        $transaction->wallet_id = $wallet->id;
        $transaction->approved_by = $data['approved_by'];
        $transaction->is_approved = $data['approved'];

        $this->repository->Add($transaction);
        $this->repository->saveChanges();

        if($this->repository->last_insert_id()) return ['info' => 'Your transaction was not recoded'];

        $transaction->id = $this->repository->last_insert_id();

        switch($transaction->transaction_type){
            case 'CREDIT':
                if($data['_wallet_'] == true) $wallet->cash_in_wallet = $wallet->cash_in_wallet + $transaction->amount;
                if($data['_cash_'] == true) $wallet->cash_at_hand = $wallet->cash_at_hand + $transaction->amount;
                break;
            case 'DEBIT':
                if($data['_cash_'] == true) $wallet->cash_at_hand = $wallet->cash_at_hand - $transaction->amount;
                if($data['_wallet_'] == true) $wallet->cash_in_wallet = $wallet->cash_in_wallet - $transaction->amount;
                break;
            case 'SECURITY':
                if($data['_wallet_'] == true) $wallet->security_cash_in_wallet = $wallet->security_cash_in_wallet + $transaction->amount;
                if($data['_cash_'] == true) $wallet->security_cash_at_hand = $wallet->security_cash_at_hand + $transaction->amount;
                break;
            default:
            $this->repository->Add($transaction);
            $this->repository->delete();
            throw new ServiceException(ServiceException::UNKNOWN_TRANSACTION_TYPE);
        }
        
        $this->repository->Add($wallet);
        $this->repository->saveChanges();

        if(!$this->repository->rows_affected()){
            $this->repository->Add($transaction);
            $this->repository->delete();
            throw new ServiceException(ServiceException::UNKNOWN_WALLET_REFERENCE);
        }

        return $transaction->reference_no;

        }catch(ServiceException $e){
            return $e->errorMessage();
        }
        
    }

    public function generateTransactionReferenceNumber(account $account , $reason = "RENT" ){
    
        $random = time() .'T'. rand(0,(10)**3 ) . $reason . '4A'.$account->id.'U'.$account->user_id;
        return $random;
    }


    public function verifyReferenceNumber($number, account $account){

        $signature = '4A'.$account->id.'U'.$account->user_id;

        if(str_ends_with($number , $signature)) return false;

        return $this->repository->reference_exists($number);


    }

    // only cash transactions can be reversed
    function reverseTransaction($number , account $account){
        if($this->verifyReferenceNumber($number , $account) == false) return ['info' => "unknown transaction reference"];
        $transaction = $this->repository->getTransaction($number);
        $wallet = $this->accountRepository->getWallet($account);
        if($transaction->transaction_type == 'CREDIT') $wallet->cash_at_hand = $wallet->cash_at_hand - $transaction->amount;
        if($transaction->transaction_type == 'DEBIT') $wallet->cash_at_hand = $wallet->cash_at_hand + $transaction->amount;
        if($transaction->transaction_type == 'SECURITY') $wallet->security_cash_at_hand = $wallet->security_cash_at_hand - $transaction->amount;
        $this->repository->Add($wallet);
        $this->repository->saveChanges();
        if(!$this->repository->rows_affected()) return ['info' => "Transaction was not reversed"];
        $this->repository->Add($transaction);
        $this->repository->delete();
        return true;
    }

    // retrieve all the dues of a tenant
    function getTenantDues($tenant_id , $paid){
        return $this->repository->getTenantDues($tenant_id , $paid);
    }

    public function setCollectionPeriod(Request $request , account $account){
        $collection_period = new collection();
        $collection_period->starting_from = $request->body->starting_from;
        $collection_period->ending_on= $request->body->ending_on;
        // complete thr collection
    }


    public function disburseCashToLandlord(Request $request , $account){
        try{
            $landlord = $this->propertyService->landlordRepository->getLandlord($request->body->landlord_id , $account);

            if(is_null($landlord)) throw new ServiceException('Landlord was not found');

            // check the advances that is not cleared off yet

            // should disburse when landlords tenants have paid
            // modified to take landlord id or an array of landlords to support eger loading
            $properties = $this->propertyService->landlordRepository->properties($landlord->id);
            
            if(empty($properties)) throw new ServiceException("Landlord has no properties being managed cannot disburse");
            $property_ids = [];
            foreach($properties as $p):
                $property_ids = $p->id;
            endforeach;

            $tenants =  $this->tenantRepository->tenantsOnProperty($property_ids,0);

            if(empty($tenants)) throw new ServiceException("Landlord has no tenants in all properties would you like to give an advance ? Yes or No");

            // fetch rent rule for each tenant
            
            // determine period from paid dues.


        }catch(ServiceException $e){
            return  $e->infoMessage();
        }
    }


    
}