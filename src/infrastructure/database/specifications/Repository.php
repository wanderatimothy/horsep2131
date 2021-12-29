<?php
namespace infrastructure\database\specifications;

use core\contracts\IRepository;
use core\pluralize;
use infrastructure\database\DB\connection;
use kernel\model;

class Repository implements IRepository {
    
    protected $table;

    public $data = [];
    
    protected connection $conn;

    private $model;

    public function __construct($class){
      $this->conn = new connection();
      $this->table = basename(pluralize::plural($class));
      $this->model = new $class;

    }

    function Add(model $model){
        $this->data[] = $model;
        return true;
    }

    function last_insert_id(){
        return $this->conn->last_insert_id;
    }
    
    function rows_affected():int{
        return $this->conn->affected_rows;
    }

    
    private function _access_clean_properties(model $model){
        $props = get_object_vars($model);
        $clean = [];
        foreach($props as $key => $value){
            
            if(!in_array($key,['timestamps_on' , 'softdeletes_on' , 'created_at' , 'last_modified'])){
                $clean[$key] = $value;
            }

        }
        return $clean;
    }


    function saveChanges(){
        
        foreach ($this->data as $m){
 
            if(is_null($m->id) || $m->id == 0){
                // insert operation
                $this->conn->runOperation(query::insert($m),$this->_access_clean_properties($m));
            }else{
                // update operation
                $this->conn->runOperation(query::update($m),$this->_access_clean_properties($m));
            }

        }

        $this->data = [];
        
    }

    function getList(model $m){
        $this->conn->runOperation(query::all($m));
        return $this->conn->results($m::class);
    }

    function delete(){
        foreach ($this->data as $m){           
            $this->conn->runOperation(query::delete($m),['id' => $m->id]);
        }
        $this->data = [];
    }

    public function findById(int $id){
         $this->conn->runOperation(query::findById($this->model),['id' => $id]);
         $results = $this->conn->results($this->model::class);
         return count($results)  == 1 ? $results[0] : null;

    }


    


}