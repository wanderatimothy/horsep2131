<?php

namespace core\aggregates;

use kernel\model;

class unit_addon extends model {

    public int $id = 0;

    public  $unit_id = 0;

    public $add_on_type_id = 0;

    public $addon_name = '';

    public $addon_cost = 0.0;

    public $addon_meter = '';

    public bool $softdeletes_on = true;

    public bool $timestamps_on = true;


    public static function foreign_keys()
    {
        return  ['unit_id' => unit::class , 'add_on_type_id' => add_on_type::class];
    }

}