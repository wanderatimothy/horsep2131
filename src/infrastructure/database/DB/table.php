<?php 

namespace infrastructure\database\DB;

use core\pluralize;
use kernel\model;


// create a table from model


class table {

    public $name; 

    public model $class; 

    public $properties;

    public function __construct(model $m)
    {
        $this->name = basename(pluralize::plural($m::class));
        $this->class = $m;
        $this->properties = get_class_vars($m::class);
    }


    function create(){

        $sql = 'drop table if exists '.$this->name.'; create table '. $this->name . ' (';

        foreach($this->properties as $prop => $value){
            if(!in_array($prop , ['timestamps_on' , 'softdeletes_on'])){
             $sql .= $prop . ' ';

            }
            switch($this->_typeOf([$value , $prop])){
                case 'integer':
                    $sql .= (strtolower($prop) == 'id')? 'integer(10) PRIMARY KEY  AUTO_INCREMENT ,' : 'integer(10) DEFAULT NULL ,';
                    break;
                case 'string':
                    $sql .= 'varchar(150) DEFAULT NULL,';
                    break;
                case 'text':
                    $sql .= 'varchar(200) DEFAULT NULL,';
                    break;
                case 'double':
                    $sql .= 'decimal(15,2) DEFAULT NULL,';
                    break;
                case 'boolean': 
                    $sql .= 'boolean DEFAULT NULL,';
                    break;
                case 'datetime': 
                    $sql .= 'datetime DEFAULT NULL,';
                    break;
                case 'timestamps': 
                    $sql .= 'created_at datetime DEFAULT CURRENT_TIMESTAMP, last_modified timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,';
                    break;
                case 'softdeletes': 
                    $sql .= ' is_deleted boolean DEFAULT 0 ,';
                    break;

                case 'blob': 
                    $sql .= 'blob DEFAULT NULL,';
                    break;
            }


        }
        

        $sql = rtrim($sql , ',') . ')engine=InnoDB';
        $connection = new connection();
        return $connection->runOperation($sql);
    }

    function drop(){

        $sql = 'drop table if exists '.$this->name;
        $connection = new connection();
        return $connection->runOperation($sql);
    }


    private function _typeOf($var){
        if(strtolower($var[1]) == 'timestamps_on') return 'timestamps';
        if(strtolower($var[1]) == 'softdeletes_on') return 'softdeletes';
        if(strtolower($var[0]) == 'date') return 'date';
        if(strtolower($var[0]) == 'datetime') return 'datetime';
        if(strtolower($var[0]) == 'text') return 'text';
        if(is_string($var[0])) return 'string';
        if(is_integer($var[0])) return 'integer';
        if(is_float($var[0])) return 'double';
        if(is_bool($var[0])) return  'boolean';
      
        return 'blob';
    }



    function addColumn($name , $type){
        // to be implemented
        return;
    }

    
    public  function constraint(){
        // create a constraint
        $foreign_keys = $this->class::foreign_keys();

        if(empty($foreign_keys)) return true;
        
        $connection = new connection();
        $sql = '';
        foreach ($foreign_keys as $fk => $table){
            $table = basename(pluralize::plural($table));
            $sql .= 'alter table `'.$this->name.'` add constraint `'.$table.'_'.$this->name.'_fk`  foreign key (`'.$fk.'`) references '.$table .' (`id`) on delete cascade on update cascade;' ;            
        }

        return $connection->runOperation($sql);
    }

} 