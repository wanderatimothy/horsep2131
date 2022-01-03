<?php

namespace core\aggregates;

use kernel\model;

class property_block extends model {

    public int $id = 0;

    public $label = '';

    public $units = 0;

    public $facilities = 0;

    public $property_id = 0;

    public bool $softdeletes_on = true;


    public static function foreign_keys()
    {
        return ['property_id' => property::class];
    }
}