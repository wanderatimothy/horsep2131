<?php

namespace core\aggregates\services;

use core\aggregates\account;
use core\aggregates\landlord;
use core\aggregates\manager;
use core\aggregates\sent_notification;
use core\aggregates\tenant;
use core\exceptions\ServiceException;
use infrastructure\database\specifications\Repositories\NotificationRepository;
use web\libs\Request;

class NotificationService  {

    public  $repository;

    public function __construct()
    {
        $this->repository = new NotificationRepository();
    }



    public function ModifyNotificationSettings (Request $request ){

        $notification = $this->repository->findById($request->id);

        if(is_null($notification)) throw new ServiceException("Unknown Settings Reference.");

        $notification->tenant_delete = $request->body->tenant_delete;

        $notification->manager_login = $request->body->manager_login;

        $notification->landlord_delete = $request->body->landlord_delete;

        $notification->payment_made = $request->body->payment_made;

        $notification->tenant_request = $request->body->tenant_request;

        $notification->tenant_complaint = $request->body->tenant_complaint;

        $notification->security_payback_request = $request->body->security_request;

        $notification->manager_complaint = $request->body->manager_complaint;

        $notification->manager_request = $request->body->manager_request;

        $notification->total_collection = $request->body->total_collection;

        $notification->tenants_due = $request->body->tenant_due;

        $this->repository->Add($notification);

        $this->repository->saveChanges();

        return $this->repository->rows_affected();

    }



   public function Notification($data){

      $notification  = new sent_notification();

      $notification->message = $data->message;

      $notification->seen = false;

      $notification->recipient_account_model = $data->recipient_account_type;

      $notification->recipient_account_id = $data->recipient_id;

      $this->repository->Add($notification);

      $this->repository->saveChanges();

      return $this->repository->last_insert_id();
   }

 
   public function markAsSeen(sent_notification $notification){

       $notification->seen = true;

       $this->repository->Add($notification);

       $this->repository->saveChanges();
 
       return $this->repository->rows_affected();

   }


   public function accountLevelNotifications(account $account){

     return $this->repository->UnseenNotifications(account::class,$account->id);

   }

   public function managerLevelNotifications(manager $manager){

    return $this->repository->UnseenNotifications(manager::class,$manager->id);

   }

   public function tenantLevelNotifications(tenant $tenant){

    return $this->repository->UnseenNotifications(tenant::class,$tenant->id);

   }

   
   public function landlordLevelNotifications(landlord $landlord){

    return $this->repository->UnseenNotifications(landlord::class,$landlord->id);
    
   }

   public function notificationTypes(){

        return array(
            'on_manager_login' => " Manager {{manage_name}} logged in. - {{timestamp}}",
            'on_tenant_delete' => "Tenant {{tenant_name}} has been deleted. - {{timestamp}} ",
            'new_role_assignment' => 'You have been assigned to {{role}} on {{property}}. - {{timestamp}}',
            'new_role_assignment_units' => "You have a new {{role}} check units assigned to you.  - {{timestamp}} ",
            'on_role_revoked' => "Your role as {{role}} has been revoked. - {{timestamp}}",
            "on_rent_payment" => "Tenant {{tenant_name}} has paid rent in cash of {{amount_paid}} {{currency}} . -{{timestamp}} ",
            'tenants_due_this_week' => "There are {{number_due}} tenants due this {{period}}",
            'tenants_due_today' => 'You have {{number_due}} today - {{timestamp}}'
        );


   }



    /**
    * 
    * creates a notification message
    * */    
   
   function sendNotification($notification_type , array $data , $messageTemplate = ""){

      if(!in_array($notification_type, array_keys($this->notificationTypes()))){
        $template = $messageTemplate;
      }else{
         $template = $this->notificationTypes()[$notification_type];
      }
      $keys = array_keys($data);
      $t_string = $template;
      foreach($keys as $key) str_replace("{{".$key."}}" , $data[$key]  , $t_string);

      $data['_config_']["message"] = $t_string;

      return $this->Notification((object)$data['_config_']);
   }




    
    
}