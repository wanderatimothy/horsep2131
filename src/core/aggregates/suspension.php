<?php

namespace core\aggregates;

use kernel\model;

class suspension extends model{

    public int $id = 0;

    public $account_id = 0;

    public $reason = "text";

    public bool $status = false;

    public bool $timestamps_on = true;

    public static function foreign_keys()
    {
        return ['account_id'  => account::class];
    }
}