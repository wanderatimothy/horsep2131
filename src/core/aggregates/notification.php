<?php

namespace core\aggregates;

use kernel\model;

class notification extends model {

    public int $id = 0;

    public bool $timestamp_on = true;

    public $tenant_delete = false;

    public $landlord_delete = false;

    public $manager_login = false;

    public $payment_made  = true;

    public $tenants_due = true;

    public $total_collection = true;

    public $security_payback_request = true;

    public $tenant_update = true;

    public $tenant_request = true;

    public $tenant_complaint = true;

    public $manager_request = true;

    public $manager_complaint = true;

    public $account_id = 0;

    
    public static function foreign_keys()
    {
        return  ["account_id" => account::class];
    }
 

}