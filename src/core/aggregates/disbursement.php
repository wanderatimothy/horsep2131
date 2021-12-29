<?php

namespace core\aggregates;

use kernel\model;

class disbursement extends model {

    public int $id = 0;

    public $landlord_id = 0;

    public $total_amount_collected = 0.00;

    public $total_disbursed = 0.00;

    public $total_commission = 0.00;

    public $total_advance_commission = 0.00;

    public $transaction_reference = "";

    public $date_of_disbursement = "datetime";

    public $comments = "text";

    public $timestamps_on = true;

    public $softdeletes_on = true;

    public $account_id = 0;
    
    
    public static function foreign_keys()
    {
        return ["account_id" => account::class , "landlord_id" =>landlord::class];
    }
}