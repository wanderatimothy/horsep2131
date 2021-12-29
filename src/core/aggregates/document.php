<?php

namespace core\aggregates;

use kernel\model;

class document extends model {

    public int $id = 0;

    public  $user_id = 0;

    public $document_path = '';

    public $module = '';

    public $model = 0;

    public $title = '';

    public $purpose='';
    
    public bool $timestamps_on = true;

    public static function foreign_keys()
    {
        return ['user_id' => user::class];
    }
}
