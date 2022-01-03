<?php 

namespace infrastructure\database\specifications;

use core\pluralize;
use infrastructure\database\DB\table;
use kernel\model;

class query {

    public static function insert( model $model){
    
        $properties = query::properties($model);

        $q_string = 'INSERT INTO '. self::table($model) . ' ('.implode(',',$properties) . ') VALUES (:' .implode( ',:', $properties ) . ')';  

        return $q_string;

    }


    public static function properties (model $model){
        $properties = get_class_vars($model::class);

        $properties  = array_filter( array_keys($properties) ,function($p){ 
            return (!in_array($p , ['timestamps_on' , 'softdeletes_on'])); 
        } );

        return $properties;

    }


    public static function update(model $model , array $filter ){
      

        $properties = get_class_vars($model::class );

        $q_string = 'UPDATE '.self::table($model). ' SET ';

        $prepared = [];

        foreach(array_keys($properties) as $key){

            if($key != "timestamps_on"):
            
                $prepared[] = ($key == "softdeletes_on") ? "is_deleted = :is_deleted" : $key . " =: ".$key;

            endif;
        }

        $q_string .= implode(',',$prepared) . " ".self::InterpretFilter($filter);

        return $q_string;


    }


    public static function all(model $model ,$columns = [] ,  array  $sortsAndLimits = null ){

        $properties =  $columns == [] ?  query::properties($model) : $columns;

        $q_string = 'SELECT '. implode(',',$properties) . " FROM ". self::table($model) .' ';
        

        if(!is_null($sortsAndLimits)){

            $q_string .= self::sortsAndLimits($sortsAndLimits);
        }
        
        
        return $q_string;

    }



    public static function delete(model $model , array $filter = ['id'  => ':id' ]){
    
        $q_string = 'DELETE FROM '. self::table($model) .'';

        $q_string .= self::InterpretFilter($filter);

        return $q_string;
    }

    public static function InterpretFilter(array $data){

        $q_string = " WHERE ";

        $condition = array();

        foreach ($data as $key => $value) {

            if(is_string($value) || is_numeric($value) || is_bool($value) || is_float($value)){

                $condition[]  = $key ." = ".$value; 

            }elseif(is_array($value)){

                $type = self::array_type($value);

                $key = self::clean_key($key);

                if($type == 'associative'){

                    if($key == 'BETWEEN'){

                        $condition[] = $value['filter'] . " BETWEEN (".implode('AND',$value['range']).")";
                    }

                    if($key == 'NOT IN' || $key == "IN"  ){

                        $condition[] = $value['filter'] . $key . " (".implode(',',$value['range']).") ";
                    }
                }
            }
        }

        $q_string .= " " . implode(" AND ",$condition);

        return $q_string;



    }

    public static function findById(model $model){

        $q_string = self::find($model,["id" => ":id"]);

        return $q_string;
    }


    public static function table(model $model){
        return  basename(pluralize::plural($model::class));
    }

    public static function find(model $model, array $filter ,array $columns = null ,  array $sortAndLimits = null){

        $properties =  is_null($columns) ? self::properties($model) : $columns;

        $table = self::table($model);

        $q_string = "SELECT " . implode(',',$properties) . ' FROM ' .$table. ' ';

        $q_string .= self::InterpretFilter($filter);
       
        if(is_null($sortAndLimits)) return $q_string;

        $q_string .= self::sortsAndLimits($sortAndLimits);

        return $q_string;



    }

    public static function sortsAndLimits(array $data){
        
        $q_string = '';

        if(isset($data['sorting'])){
            $sorting = array();

            foreach($data['sorting'] as $key => $value ){

                $sorting [] = $key ." ".$value;
                
            }

            $q_string .= " ORDER BY " . implode($sorting);
        }

        if(isset($sortAndLimits['limiting'])){

            $q_string .= "LIMIT" . implode(",",$sortAndLimits["limiting"]);

        }

        return $q_string;
    }

    public static function clean_key($key){
        return trim(strtoupper($key));
    }

    public static function array_type(array $array) {
        $string_index_count = 0;

        $numeric_index_count = 0; 

        foreach($array as $key => $value){
            if(is_numeric($key)){
                $numeric_index_count ++;
            }
            if(is_string($key)){
                $string_index_count ++;
            }
        }

        if(($string_index_count > 0 && $numeric_index_count == 0 ) || ($string_index_count > 0 && $numeric_index_count > 0) ) return 'associative';

        if($string_index_count == 0 && $numeric_index_count > 0 ) return 'indexed';

    }


}
