<?php

namespace core\aggregates;

use kernel\model;

class property extends model {

    public int $id = 0;

    public int $landlord_id = 0;

    public string $property_label = '';

    public int $type_id = 0;

    public string $location = '';

    public float $rent_amount = 0.00;

    public int $allowed_occupants = 0;

    public bool $has_units = false;

    public bool $softdeletes_on = true;

    public bool $timestamps_on = true;


    public static function foreign_keys()
    {
        return [ 'landlord_id' => landlord::class , "type_id" => property_type::class   ];
    }
}