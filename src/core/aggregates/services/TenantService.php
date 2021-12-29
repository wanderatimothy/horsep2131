<?php

namespace core\aggregates\services;

use core\aggregates\account;
use core\aggregates\tenant;
use core\aggregates\tenant_due;
use core\aggregates\wallet;
use core\exceptions\ServiceException;
use infrastructure\database\specifications\Repositories\TenantRepository;
use web\libs\Request;

final class TenantService extends _BaseService {

    public TenantRepository $repository;

    public PropertyService $propertyService;

    public SettingsService $settingsService;

    private PaymentService $paymentService;

    private wallet $wallet;

    public function __construct(){

        parent::__construct();

        $this->repository = new TenantRepository();

        $this->propertyService = new PropertyService();

        $this->settingsService = new SettingsService();

        $this->paymentService = new PaymentService();

    }

    public function  createTenant(Request $request , account $account , tenant $tenantObject = null){

            try{
            
            $onBoardingSettings = $this->settingsService->OnboardingSettings($account);

            $this->wallet = $this->accountRepository->getWallet($account);
            
            $unit = $this->propertyService->repository->getUnit($request->body->unit_id);
           
            if(is_null($unit)) throw new ServiceException(ServiceException::UNIT_DOSE_NOT_EXIST);

             $property = $this->propertyService->repository->getById($unit->property_id , $account);

            if(is_null($property)) throw new ServiceException(ServiceException::UNIT_NOT_ON_PROPERTY);

             $landlord = $this->propertyService->landlordRepository->findById($property->id);

            if($unit->number_of_occupants ==  $unit->occupants_limit) throw new ServiceException(ServiceException::UNIT_AT_CAPACITY);
            
            
            if($onBoardingSettings->security_deposit == true && $onBoardingSettings->allow_without_security == false) {
                // strict enforce of security rules
                if(!isset($request->body->security_payment_ref)) throw new ServiceException("Provide the security payment reference");
                // validate payment reference no
                $payment = $this->paymentService->verifyReferenceNumber($request->body->security_payment_ref,$account);

                if($payment == false) throw new ServiceException("Invalid security Payment Reference");

                if($payment->wallet_id !== $this->wallet->id) throw new ServiceException("Invalid security payment reference");
            }


           if($onBoardingSettings->payment_before_entry == true && $onBoardingSettings->allow_entry_before_payment == false){
                // strict enforce payment before 
                if(!isset($request->body->entry_deposit_ref)) throw new ServiceException("Provide the entry deposit payment reference");

                $payment = $this->paymentService->verifyReferenceNumber($request->body->entry_deposit_ref,$account);

                if($payment == false) throw new ServiceException("Invalid entry deposit payment Reference");

                if($payment->wallet_id !== $this->wallet->id) throw new ServiceException("Invalid entry deposit payment reference");
                
           }

            $tenant = new tenant();
            $tenant->names = strtoupper($request->body->tenant_names);
            $tenant->gender = $request->body->gender;
            $tenant->email = $request->body->email;
            $tenant->contact = $request->body->contact;
            $tenant->gender = $request->body->gender;
            $tenant->number_of_people = $request->body->population;
            $tenant->number_of_pets = $request->body->pets_population;
            $tenant->emergency_person_names = $request->body->emergency_person;
            $tenant->emergency_person_contact = $request->body->emergency_contact;
            $tenant->unit_id = $request->body->unit_id;
            $tenant->date_of_entry = $request->body->entry_date;
            $tenant->next_due_date = $request->body->next_due_date;

            // calculate next due date

            if($request->hasFiles()){
                // file upload goes here
                $upload = $this->fileUploadService->uploadSingleImage((object) $request->files->tenant_photo,'tenants/'.str_replace(' ','@_@',$tenant->names.time()));
                
                if(!$upload) throw new ServiceException(ServiceException::FILE_NOT_SAVED,["message" => $this->fileUploadService->errors[0]]);

                $tenant->tenant_photo = $this->fileUploadService->uploadedFiles[0];

            }


            $this->repository->Add($tenant);
            $this->repository->saveChanges();

             
            if($this->repository->last_insert_id()){

                $tenant->id = $this->repository->last_insert_id();
                 $unit->number_of_occupants = $unit->number_of_occupants + 1;
                if($unit->number_of_occupants == $unit->occupants_limit) $unit->is_occupied = true;
                 $this->repository->Add($unit);
                 $landlord->tenants = $landlord->tenants + 1;
                 $account->tenants = $account->tenants  + 1;
                 $this->repository->Add($landlord);
                 $this->repository->Add($account);
                 $this->repository->saveChanges();

                 if($this->repository->rows_affected()){

                    if($request->body->has_custom_fields){
                        $fields = $this->settingsService->getCustomFields(tenant::class , $account);
                        $values = get_object_vars($request->body);
                        foreach($fields as $field){
                            $this->settingsService->saveCustomValue(tenant::class , $tenant->id , $field, $values[strtolower(str_replace(" ","_",$field->name))]);                
                           }
                       }

                     return true;
                 }else{
                     $this->repository->data = [];
                     $this->repository->Add($tenant);
                     $this->repository->delete();
                     
                     
                     throw new ServiceException(ServiceException::TENANT_NOT_SAVED );
                 }
            }else{

                throw new ServiceException(ServiceException::TENANT_NOT_SAVED );
            }
        }catch(ServiceException $exception){
            return $exception->errorMessage();
        }

        
    }


    public function getTenants(account $account , bool $deleted = false){
        
        $units = $this->propertyService->getAllUnits($account , false );
        if(empty($unit)) return [];
        $ids = [];
        foreach ($units as $unit) $ids[] = $unit->id;

        return $this->repository->getTenantsFrom($ids,$deleted);
    }



    public function getTenantsDue(account $account ,$period_start, $period_end){

        $properties = $this->propertyService->get_property_ids($account);

        if(empty($properties)) return  [];

        $units_with_at_least_a_tenant  = $this->propertyService->repository->getUnitsWithTenants($properties);

        if(empty($units_with_at_least_a_tenant)) return [];

        $filter = [];

        foreach($units_with_at_least_a_tenant as $unit) $field[] = $unit['id'];

        return $this->repository->getTenantsDueWithPeriods($filter,$period_start,$period_end);

    }


    public function getTenantsDueToday(account $account){

        $properties = $this->propertyService->get_property_ids($account);

        if(empty($properties)) return  [];

        $units_with_at_least_a_tenant  = $this->propertyService->repository->getUnitsWithTenants($properties);

        if(empty($units_with_at_least_a_tenant)) return [];

        $filter = [];

        foreach($units_with_at_least_a_tenant as $unit) $field[] = $unit['id'];

        return $this->repository->getTenantsDueToday($filter);

    }



    function createRentDue($tenant_id){

        $tenant = $this->repository->findById($tenant_id);

        if(is_null($tenant)) return false;

        $unit = $this->propertyService->repository->getUnit($tenant->id);

        $renting_mode_settings = $this->settingsService->settingsRepository->getRentingModeSetting($tenant->unit_id , unit::class);
        
        $rent_due = new tenant_due();

        $rent_due->amount = $unit->rent_amount;

        $rent_due->paid_amount = 0;
        
        $rent_due->tenant_id = $tenant->id;

        $rent_due->exempted = false;

        $rent_due->item = "RENT";

        if($renting_mode_settings->pay_condition == "after_cycle"){
            // pay for cycle of occupance
            $str = $tenant->next_due_date." - ".$renting_mode_settings->cycle;

            $rent_due->period = "From ".date("Y-m-d",strtotime($str)). " To ".$tenant->next_due_date;

        }
        elseif($renting_mode_settings->pay_condition == "before_cycle"){

            // pay for cycle in advance

            $str = $tenant->next_due_date." + ".$renting_mode_settings->cycle;

            $rent_due->period = "From ".$tenant->next_due_date . " To ".date("Y-m-d",strtotime($str));

        }
        else{
            // set pay condition
            return false;
        }

       $this->repository->Add($rent_due);
       
       $this->repository->saveChanges();

       return ($this->repository->last_insert_id())?true :false;



    }


    
    

  
}