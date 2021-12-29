<?php 

namespace core\aggregates;

use kernel\model;

class wallet extends model{

    public int $id = 0;

    public $balance = 0.00;

    public $cash_at_hand = 0.00;

    public $cash_in_wallet = 0.00;

    public $security_cash_at_hand = 0.00;

    public $security_cash_in_wallet = 0.00;

    public $account_id = 0;

    public bool $softdeletes_on = true;

    public bool $timestamps_on = true;

    public static function foreign_keys()
    {
        return ["account_id" => account::class];
    }
}