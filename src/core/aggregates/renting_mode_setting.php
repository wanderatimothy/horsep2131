<?php
namespace core\aggregates;

use kernel\model;

class renting_mode_setting extends model{

    public int $id = 0;

    public $mode_id = 0;

    public $model = '';

    public $model_id = 0;

    public bool $timestamps_on = true;

    public bool $softdeletes = true;


    public static function foreign_keys()
    {
        return  ['mode_id' => renting_mode::class];
    }

}