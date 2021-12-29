<?php

namespace core\aggregates;

use kernel\model;

class transaction extends model{

    public int $id = 0;

    public $amount = 0.00;

    public $transaction_date = "datetime";

    public $reference_no = "";

    public $transaction_type = "";

    public $is_approved = false;

    public $approved_by = "";

    public $wallet_id = 0;

    public $reason = "";

    public bool $softdeletes_on = true;

    public bool $timestamps_on = true;

    public static function foreign_keys()
    {
        return ["wallet_id" => wallet::class];
    }

}