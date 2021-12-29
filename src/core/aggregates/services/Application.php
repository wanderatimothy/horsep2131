<?php


namespace core\aggregates\services;

use core\aggregates\account;

final class Application {

    private TenantService $TenantService;

    private AccountService $AccountService;

    private ManagerService  $ManagerService;

    private NotificationService $NotificationService;

    private PropertyService $PropertyService;

    private SettingsService $SettingsService;

    private PaymentService $PaymentService;

    private AuthService $AuthService;



    public function __construct()
    {
        $this->AuthService = new AuthService();

        $this->AccountService = new AccountService();

        $this->ManagerService = new ManagerService();
        
        $this->SettingsService = new SettingsService();

        $this->PropertyService = new PropertyService();

        $this->PaymentService = new PaymentService();

        $this->TenantService = new TenantService();

        $this->NotificationService = new NotificationService();

    }



    


    public function checkAndNotifyDues(account $account){

        $starting_from = DateService::period("Sunday this week"); 

        $ending_on = DateService::period("Saturday this week");

        $tenants =  $this->TenantService->getTenantsDue($account,$starting_from,$ending_on );

        if(empty($tenants)) return true;

        $this->NotificationService->sendNotification('tenants_due_this_week',array(
            "template_data" => array(
                "number_due" => count($tenants),
                "period" => "week"
            ),
            "_config_" => array(
                'recipient_account_type' => account::class,
                'recipient_id' => $account->id,
            )
        ));

    }

    public function dueToday(account $account){

        $tenants =  $this->TenantService->getTenantsDueToday($account);

        if(empty($tenants)) return true;

        $this->NotificationService->sendNotification('tenants_due_today',array(
            "template_data" => array(
                "number_due" => count($tenants),
                "timestamp" => DateService::current_timestamp()
            ),
            "_config_" => array(
                'recipient_account_type' => account::class,
                'recipient_id' => $account->id,
            )
        ));

       foreach ($tenants as $tenants){
            // create them a rent due;
            $this->TenantService->createRentDue($tenants->id);
       }

       return true;

    }







}