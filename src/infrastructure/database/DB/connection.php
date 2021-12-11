<?php
namespace infrastructure\database;

use PDO;
use PDOException;
use PDOStatement;

class connection {

    private PDOStatement $pdo_statement;

    public $affected_rows; 

    public $last_insert_id;

    public $last_sql;

    public $last_error;

    function connect(){
        $configs = $this->get_db_config();
        $dns = 'mysql:host='.$configs->host.";dbname=".$configs->database;
        try{
          $connection = new PDO($dns,$configs->user,$configs->password);
          return $connection;
        }catch(PDOException $e){
            echo $e->getMessage();
            die;
        }
    }

    private function get_db_config(){
        $data = file_get_contents('src/infrastructure/db/dbconfig.json');
        $configs = json_decode($data);
        return $configs;
    }


    function runOperation($sql,array $parameters = null){
        $c = $this->connect();
        try{
            if(is_null($parameters)){

                $stmt = $c->query($sql);

            }else{
                $stmt = $c->prepare($sql);
    
                $stmt->execute($parameters);
            }

            $this->pdo_statement = $stmt;
            $this->affected_rows = $stmt->rowCount();
            $this->last_insert_id = $c->lastInsertId();
            $this->last_sql = $sql;
            return ($stmt != false && is_object($stmt))? true : false; 

        }catch(PDOException $e){
            $this->last_error = $c->errorInfo();
            echo $e->getMessage();
            return false;
        }
       
    }


    function results(string $class = null){
        if(is_null($class)) return $this->pdo_statement->fetchAll(PDO::FETCH_ASSOC);
        $resultSet = array();
        while ( $row = count($this->pdo_statement->fetchObject($class))){
            $resultSet[] =  $row;
        }
        return $resultSet;
    }



    function startTransaction(){
        $c = $this->connect();
        $c->beginTransaction();
        return $c;
    }

    function rollBack(PDO $connection){
        $connection->rollBack();
        $connection = null;
        return true;
    }


    function commitTransaction(PDO $connection){
        $connection->commit();
        $connection = null;
        return true;
    }
















}