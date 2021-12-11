<?php
namespace infrastructure\database\specifications;

use infrastructure\database\connection;
use kernel\model;

class dbal {


    
    protected $data = array();

    private $_added_index = array();

    private $_remove_index = array();

    protected connection $_connection;

    function setConnection(connection $conn){
        $this->_connection = $conn;
    }

    function Add(model $model){
        $this->data[] = $model;
        return true;
    }
    
    function saveChanges(){
        
        foreach ($this->data as $m){
 
            if(is_null($m->id) || $m->id == 0){
                // insert operation
                $this->_connection->runOperation(query::insert($m),get_object_vars($m));
            }else{
                // update operation
                $this->_connection->runOperation(query::update($m),get_object_vars($m));
            }

        }
        
    }

    function getList(model $m){
        $operation = $this->_connection->runOperation(query::all($m));
        return $this->_connection->results($m::class);
    }



}