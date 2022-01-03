<?php

namespace core\resources;

use core\aggregates\property;
use infrastructure\database\specifications\Repository;

class property_block_resource extends _resource {


    public function __construct($id , $label , $units , $facilities , $property_id  , $is_deleted , $created_at , $last_modified)
    {
        
        $this->data = array(
            'id' => $id,
            'label' => $label,
            'units' => $units,
            'facilities' => $facilities,
            'property_id' => $property_id,
            'property' => (new Repository(property::class)) -> findById($property_id),
            'is_delete' => $is_deleted,
            'last_modified' => $last_modified,
            'created_at' => $created_at
        );
        
    }
}