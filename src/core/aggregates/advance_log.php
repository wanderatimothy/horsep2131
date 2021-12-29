<?php

namespace core\aggregates;

use kernel\model;

class advance_log extends model{

    public int $id = 0;

    public $advance_id = 0;

    public $amount_cleared = 0.00;

    public $amount_remaining = 0.00;
    
    public bool $softdeletes_on = true;

    public bool $timestamps_on = true;

    public static function foreign_keys()
    {
        return ["advance_id" => advance::class ];
    }


}