<?php

namespace core\aggregates;

use kernel\model;

class renting_mode extends model {

    public int $id = 0;

    public $cycle = '';

    public $grace_period = '';

    public $pay_condition = "after_cycle";

    public $timestamps_on = true;

    public $softdeletes_on = true;

    public $account_id = 0;

    public static function foreign_keys()
    {
        return  ['account_id' => account::class];
    }
}