<?php
namespace core\aggregates;

use kernel\model;

class cf_value extends model {

    public int $id = 0;

    public $value = "";

    public $model = '';

    public $model_id = 0;

    public $cf_id = 0;

    public static function foreign_keys()
    {
        return  ['cf_id' => custom_field::class];
    }
}