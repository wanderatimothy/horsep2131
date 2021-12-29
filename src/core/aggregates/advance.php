<?php

namespace core\aggregates;

use kernel\model;

class advance extends model {

    public int $id = 0;

    public $landlord_id = 0;

    public $amount_advanced = 0.00;

    public $advance_commission_rate = 0.00;

    public $amount_cleared = 0.00;

    public $balance_remaining = 0.00;

    public $date_advanced = "datetime";

    public $date_cleared = "datetime";

    public bool $advance_is_cleared = false;

    public $disbursement_id = 0;

    public $timestamps_on = true;
    
    public bool $softdeletes_on =true;
    
    public static function foreign_keys()
    {
        return ["disbursement_id" => disbursement::class , "landlord_id" =>landlord::class];
    }

    
}