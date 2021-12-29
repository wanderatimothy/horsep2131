<?php
namespace kernel\containers;

use Exception;

class _Mediator {

    public $CustomException; 

    public function setExceptionHandler($class,$handler){
        $this->boundaryExceptions[$class] = $handler;
    }
    
    public function operation(callable $function , $dataset = [] ){
        try{
           $operation = call_user_func_array($function , $dataset);
        }catch(Exception $e){
            
            $keys = array_keys($this->boundaryExceptions);

            if(in_array($e::class , $keys)){
                if(  is_array($this->boundaryExceptions[$e::class])  &&  !is_null($this->boundaryExceptions[$e::class]) ){
                  $operation = call_user_func($this->boundaryExceptions[$e::class]);
                 }
                else{
                 $operation = $e->getMessage();
                } ;
            }else{
                $operation = ['error' => "Sorry your request could not be processed contact support"];
            }

        }
        finally{
            return $operation;
        }
        
    }
    

}