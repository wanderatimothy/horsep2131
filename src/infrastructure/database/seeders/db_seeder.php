<?php

namespace  infrastructure\database\seeders;

use core\aggregates\floor;
use core\aggregates\property_type;
use core\aggregates\subscription_type;
use infrastructure\database\seeders\seeder;


final class db_seeder {


    public static function seedData(){
    $seeder = new seeder(function(){
    
    $data  = [];

    $sub1 = new subscription_type();
    $sub1->accounts_allowed = 5;
    $sub1->landlords_allowed = 3;
    $sub1->properties_allowed = 5;
    $sub1->units_allowed = 50;
    $sub1->manage_documents = false;
    $sub1->managers_allowed = 3;

    $sub2 = new subscription_type();
    $sub2->accounts_allowed = 10;
    $sub2->landlords_allowed = 10;
    $sub2->properties_allowed = 10;
    $sub2->units_allowed = 100;
    $sub2->manage_documents = false;
    $sub2->managers_allowed = 10;

    $data[] = $sub1;
    $data[] = $sub2;


    $prop_type1 = new property_type();
    $prop_type1->has_shareable_units = false;
    $prop_type1->has_units = true;
    $prop_type1->is_sharable = false;
    $prop_type1->name = "Residential";
    $prop_type1->description = "Suitable for a chain of family homes , and estates etc ";

    $prop_type2 = new property_type();
    $prop_type2->has_shareable_units = true;
    $prop_type2->has_units = true;
    $prop_type2->is_sharable = false;
    $prop_type2->name = "Hostels";
    $prop_type2->description = "Suitable for residential hostels , supports both single and multiple tenants in a unit ";

    $data[] = $prop_type1;
    $data[] = $prop_type2;

    $floor1 =  new floor();
    $floor1->floor_name = "N/A";
    $floor2 =  new floor();
    $floor2->floor_name = "Ground Floor";
    $floor3 =  new floor();
    $floor3->floor_name = "1 st Floor ";

    $data[] = $floor1;
    $data[] = $floor2;
    $data[] = $floor3;

  
    
    return $data;

    });
    $seeder->run();
    
    }


}

