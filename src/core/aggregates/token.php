<?php
namespace core\aggregates;

use kernel\model;

class token extends model {

    public int $id = 0;

    public $token_hash = "text";

    public bool $timestamps_on = true;
    
    public $user_id  = 0;

    public static function foreign_keys()
    {
        return ['user_id' => user::class];
    }

}