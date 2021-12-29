<?php
namespace core\aggregates;

use kernel\model;

class unit extends model{

    public int $id = 0;

    public int $property_id = 0;

    public string $label = '';

    public bool $is_occupied = false;

    public $rent_amount = 0.00;

    public  $occupants_limit =  0;

    public $number_of_occupants = 0;                       

    public $rooms = 0;

    public $facilities = 0;

    public $self_contained = false;

    public $description  =  '';

    public int $floor_id = 0;

    public $timestamps_on = true;

    public $softdeletes_on = true;

    public static function foreign_keys()
    {
        return ['property_id' => property::class , 'floor_id' => floor::class ];
    }
}