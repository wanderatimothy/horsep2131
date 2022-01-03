<?php 

namespace core\aggregates;

use kernel\model;

class on_boarding_rule extends model {

    // default rule

    public int $id = 0;

    public $security_deposit = false;

    public $payment_before_entry = false;

    public $cycles_to_pay_before_entry = 3;

    public $account_id = 0;

    public $timestamps_on = true;

    public static function foreign_keys()
    {
        return ['account_id' => account::class];
    }
}