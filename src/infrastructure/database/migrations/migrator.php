<?php
namespace infrastructure\database\migrations;

// use infrastructure\logger\Logger;

class migrator{

    public static $__migrations__dir__  = __APP_INFRASTRUCTURE__DIR.'database/migrations/';

    public static function migrate(){

        require_once migrator::$__migrations__dir__ .'m_01'.extension;
      
    }


    public static function run(callable $actions){
        echo "running <br/>";
        
        $tables = call_user_func($actions);
        $created = [];
        // $logger = new Logger('migrations.log','migrations_log');
        // $logger->log()->info('Migration started \n');

        foreach ($tables as $table){

            if($table->create()){
                $created[] = $table;
                echo "Table " .$table->name ."created <br/>";
                // $logger->log()->info('Table '.$table->name.' was  created \n');
            }else{
                echo "Table " .$table->name ." not created <br/>";

                // $logger->log()->error('Table '.$table->name.' was not created \n');
                break;
            }
        }

        foreach ($created as $table){
            if($table->constraint()){
                echo "Table  " .$table->name ." constraints created <br/>";

                // $logger->log()->info('Table '.$table->name.' constraints were  created \n');
            }else{
                echo "Table " .$table->name ." constraints not created <br/>";

                // $logger->log()->error('Table '.$table->name.' constraint were not created \n');
                break;
            }
        }

        // $logger->log()->info('Migration Complete ! \n');

        echo "migration complete ! <br/>";

    
        if(count($created) == count($tables) ){
            echo "migrations successful";
            // $logger->log()->success('Migration was successful \n');
        }else{
            // $logger->log()->info('Migration was unsuccessful \n');
            echo "migration unsuccessfull ";
        }

        echo 'migrated';
       
    } 


    public static function reverse(callable $function ){
        $tables = call_user_func($function);
        foreach ($tables as $table){
            if($table->drop()){

                echo "Table  " .$table->name ."  dropped <br/>";

                // $logger->log()->info('Table '.$table->name.' constraints were  created \n');
            }else{
                echo "Table " .$table->name ." not dropped <br/>";

                // $logger->log()->error('Table '.$table->name.' constraint were not created \n');
                break;
            }
        }
    }

    
}