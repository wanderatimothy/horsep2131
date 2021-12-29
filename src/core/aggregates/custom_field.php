<?php

namespace core\aggregates;

use kernel\model;

class custom_field extends model {

    public int $id = 0;

    public  $account_id = 0;

    public $model = '';

    public $field_id = 0;

    public $model_id = 0;

    public $name = "";

    public bool $timestamps_on =true;

    public bool $softdeletes_on = true;

    public static function foreign_keys()
    {
        return ['account_id' => account::class , 'field_id' => field::class];
    }


}