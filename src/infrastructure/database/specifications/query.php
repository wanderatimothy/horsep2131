<?php 

namespace infrastructure\database\specifications;

use core\pluralize;
use kernel\model;

class query {

    public static function insert( model $model){
    
        $properties = query::properties($model);

        $q_string = 'INSERT INTO '.basename(pluralize::plural($model::class)) . ' ('.implode(',',$properties) . ') VALUES (:' .implode( ',:', $properties ) . ')';  

        return $q_string;

    }


    public static function properties (model $model){
        $properties = get_class_vars($model::class);

        $properties  = array_filter( array_keys($properties) ,function($p){ 
            return (!in_array($p , ['timestamps_on' , 'softdeletes_on'])); 
        } );

        return $properties;

    }


    public static function update(model $model){
      

        $properties = get_class_vars($model::class);

        $q_string = 'update '.basename(pluralize::plural($model::class)). ' set';

        foreach($properties as $key => $value){
            if($key != 'timestamps_on'){
                if($key == "softdeletes_on") {
                    $q_string .= ' is_deleted =:is_deleted ,';
                }else{
                    $q_string .= ' '.$key. ' =:'.$key. ' ,';
                }
            }
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


    public static function findById(model $model){
        $properties = query::properties($model);
        $q_string = 'select '. implode(',',$properties) ." from ". basename(pluralize::plural($model::class)) . '  where id = :id ';
        return $q_string;
    }


}
