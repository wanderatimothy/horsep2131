<?php

namespace core\aggregates;

use kernel\model;

class state_machine extends model {

    public int $id = 0;

    public  $user_id = 0;

    public $module = '';

    public $model = 0;

    public $status = '';

    public $previous='';
    
    public bool $timestamps_on = true;

    public static function foreign_keys()
    {
        return ['user_id' => user::class];
    }
}
