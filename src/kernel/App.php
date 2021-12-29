<?php
namespace kernel;

class App {

    private $baseDirectory = 'src/';

    public function init(string $name){
        // initialize application instance
            define('app_base_dir',$this->baseDirectory);
            define('extension', '.php');
            define('app_name', $name);
            $this->auto_load_register(function($class_name){
            $app_class = app_base_dir . str_replace('\\','/',$class_name) .extension;
            if(file_exists($app_class)){
                include_once $app_class;
            } 
            else {
                include_once $class_name . extension;
            }
            });
            
            include_once 'src/web/program.php';
    }


    function register($function , array $params){
        return  call_user_func_array($function , $params);
    }

    function load_resources(array $resources){
        foreach($resources as $resource){
            include_once $resource;
        }
    }



    function auto_load_register(callable $function){
        return spl_autoload_register($function);
    }
    
}
