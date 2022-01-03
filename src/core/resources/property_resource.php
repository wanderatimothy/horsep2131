<?php

namespace core\resources;

use core\aggregates\landlord;
use core\aggregates\property_type;
use infrastructure\database\specifications\Repository;

class property_resource  extends _resource {


    public function __construct($id , $property_label , $type_id ,$location , $blocks , $has_units , $is_deleted , $created_at , $last_modified , $landlord_id)

    {
        $this->data = array(

            'id' => $id,
            'label' => $property_label,
            'location' => $location,
            'type_id' => $type_id,
            'type' => (new Repository(property_type::class))->findById($type_id),
            'landlord_id' => $landlord_id,
            'landlord' => (new Repository(landlord::class))->findById($landlord_id),
            'is_deleted' => $is_deleted,
            'created_at' => $created_at,
            'last_modified'=> $last_modified,
            'blocks' => $blocks,
            'has_units' => $has_units
        );

    }
}