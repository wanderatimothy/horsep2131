<?php

namespace core\resources;

use core\aggregates\floor;
use core\aggregates\property_block;
use infrastructure\database\specifications\Repository;

class unit_resource extends _resource {


    public function __construct($id,$label,$floor_id,$block_id,$occupants_limit,$number_of_occupants,$facilities,$self_contained,$rooms,$is_occupied,$rent_amount,$is_deleted,$created_at,$last_modified,$security_deposit,$rent_deposit_before_entry,$cycles_to_pay_before_entry,$security_deposit_amount,$description)
    {
        
        $this->data = array(
            'id' => $id,
            'label'=>$label,
            'floor_id'=>$floor_id,
            'floor'=> (new Repository(floor::class))->findById($floor_id),
            'block_id' => $block_id,
            'block' => (new Repository(property_block::class))->findById($block_id),
            'limit' => $occupants_limit,
            'occupants' => $number_of_occupants,
            'facilities' => $facilities,
            'is_self_contained' => $self_contained,
            'rooms' => $rooms,
            'rent' => $rent_amount,
            'is_occupied' => $is_occupied,
            'is_deleted' => $is_deleted,
            'created_at' => $created_at,
            'security_deposit' => $security_deposit,
            'pay_before_entry' => $rent_deposit_before_entry,
            'initial_cycles' => $cycles_to_pay_before_entry,
            'security' => $security_deposit_amount,
            'last_modified' => $last_modified,

        );
    }

}