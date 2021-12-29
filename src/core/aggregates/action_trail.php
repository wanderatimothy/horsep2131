<?php
namespace core\aggregates;

use kernel\model;

class action_trail extends model{

    public int $id = 0;

    public $model = 0;

    public $model_id = 0;

    public $action = "";

    public bool $timestamps_on = true;

    public $account_id = 0;
        
    public static function foreign_keys()
    {
        return ["account_id" => account::class , "landlord_id" =>landlord::class];
    }
}