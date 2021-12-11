<?php 

namespace infrastructure\database\specifications;

use core\pluralize;
use kernel\model;

class query {

    public static function insert( model $model){
    
        $properties = query::properties($model);

        $q_string = 'insert into '.basename(pluralize::plural($model::class)) . ' ('.implode(',',$properties) . ' values (:' .implode( ',:', $properties ) . ')';  
        
        return $q_string;

    }


    public static function properties (model $model){
        $properties = get_class_vars($model::class);

        $properties  = array_filter($properties ,function($p,$i){ return (strtolower($p) != "foreign_keys"); } );
        
        return $properties;

    }


    public static function update(model $model){
      
        $properties = query::properties($model);

        $q_string = 'update '.basename(pluralize::plural($model::class)). 'set';

        foreach($properties as $p){
            $q_string .= ' '.$p. ' =:'.$p. ' ,';
        }
        $q_string = rtrim($q_string , ',') . ' where id = :id';

        return $q_string;


    }


    public static function all(model $model ){

        $properties = query::properties($model);

        $q_string = 'select '. implode(',',$properties) ." from ". basename(pluralize::plural($model::class));
        
        return $q_string;

    }



    public static function delete(model $model){
    
        $q_string = 'delete from '.basename(pluralize::plural($model::class)) .' where id = :id ';

        return $q_string;
    }


}
