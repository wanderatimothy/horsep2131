<?php

namespace core\aggregates;

use kernel\model;

class payment extends model {

    public int $id = 0;

    public $date_payment = "datetime";

    public $amount_paid = 0;

    public $transaction_ref = "";

    public $reason = "";

    public $model = "";

    public $receipt = "";

    public $receipt_scan = "text";

    public $model_id = 0;

    public $payment_mode = '';

    public $account_id =0;

    public static function foreign_keys()
    {
        return ["account_id" => account::class];
    }

}