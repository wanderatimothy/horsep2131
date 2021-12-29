<?php

namespace core\aggregates;

use kernel\model;

class tenant_due extends model {

    public int $id = 0;

    public  $item = "";

    public  $period = "";

    public $amount = 0.0;

    public $tenant_id = 0;

    public $paid_amount = 0.0;

    public $exempted = false;

    public $exemption_reason = "";

    public $is_paid = false;

    public bool $timestamps_on = true;
    
    public static function foreign_keys()
    {
        return ['tenant_id' => tenant::class];
    }
}