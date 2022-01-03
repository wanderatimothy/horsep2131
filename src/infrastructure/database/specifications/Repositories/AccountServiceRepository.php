<?php
namespace infrastructure\database\specifications\Repositories;

use core\aggregates\account;
use core\aggregates\landlord;
use core\aggregates\notification;
use core\aggregates\sent_notification;
use core\aggregates\subscription_type;
use core\aggregates\user;
use core\aggregates\wallet;
use infrastructure\database\specifications\query;
use infrastructure\database\specifications\Repository;

class AccountServiceRepository {

    public Repository $accounts_repo;

    private Repository $subscription_plan_repo;

    private Repository $landlords_repo;

    private Repository $wallet_repo;

    private Repository $notification_repo;

    public Repository $notification_settings;

    public function __construct()
    {

        $this->accounts_repo = new Repository(account::class);

        $this->subscription_plan_repo = new Repository(subscription_type::class);

        $this->landlords_repo = new Repository(landlord::class);

        $this->wallet_repo = new Repository(wallet::class);

        $this->notification_repo = new Repository(sent_notification::class);

        $this->notification_settings = new Repository(notification::class);


    }

    public function create_account(user $user , $plan = 1 ){

        $account = new account;
        $account->user_id = $user->id;
        $account->subscription_type_id = $plan;
        $account->business_contact = $user->contact;

        $this->accounts_repo->addToQue($account);
        $this->accounts_repo->saveChanges();

        if(!$this->accounts_repo->last_insert_id()) return false;

        $account->id = $this->accounts_repo->last_insert_id();

        $wallet = new wallet;
        $wallet->account_id = $account->id;
        $landlord = new landlord;
        $landlord->names = 'YOUR ACCOUNT';
        $landlord->user_id = $user->id;
        $notification_settings = new notification;
        $notification_settings->account_id = $account->id;

        $this->notification_settings->addToQue($notification_settings)->saveChanges();

        $this->wallet_repo->addToQue($wallet)->saveChanges();

        if(! $this->wallet_repo->addToQue($wallet)->saveChanges()->last_insert_id()){
            
            $this->accounts_repo->destroy(["id" => $account->id]);

            return false;
        }

        if(! $this->landlords_repo->addToQue($landlord)->saveChanges()->last_insert_id()){
            
            $this->accounts_repo->destroy(["id" => $account->id]);
            
            return false;
        }

        return true;




    }


    function user_account_details( user $user){

        $res =  $this->accounts_repo->find(["user_id" => ":user_id"],['*'],["user_id" => $user->id]);
        
         if(count($res)  == 0 ) return null;

         $resource = [
            'account' => $res[0],
            'subscription_plan' => $this->subscription_plan_repo->findById($res[0]->subscription_type_id),
         ];

         return $resource;
    }
    

   function landlords(account $account , bool $deleted = false, array $pagination =  null){

        return $this->landlords_repo->find([
            "is_deleted" => ':is_deleted',
            "user_id" => ":user_id"
        ],['*'],[
            "user_id" => $account->user_id,
            'is_deleted' => $deleted
        ],$pagination);

   }


  public function getWallet(account $account){

      $res = $this->wallet_repo->find(["account_id" => ":account_id"],[],["account_id" => $account->id]);

      if(count($res)  == 0 ) return null;

      return $res [0];

 }

 public function accountNotifications(account $account , bool $seen = false ,  array $sort_and_limit = null){

     return $this->notification_repo->find([
        "seen" => ":seen",
        "recipient_account_model" => ":model",
        "recipient_account_id" => ":account_id",
     ],[],[
        'model' => $account::class,
        'seen' => $seen,
        'account_id' => $account->id        
     ],$sort_and_limit);
    
 }
 

}