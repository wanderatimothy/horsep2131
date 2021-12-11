<?php
namespace kernel;

class App {

    private $name;

    public function init(string $name){
        // initialize application instance
         $this->name = $name;
         include_once 'src/web/program.php';
    }


    function register(callable $function , array $params){
        return  call_user_func_array($function , $params);
    }
    
}
