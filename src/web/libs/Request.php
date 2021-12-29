<?php
namespace web\libs;

use RuntimeException;

class Request {
 public $method;

 public function __construct()
 {
    $this->method = $this->getRequestMethod();
    $this->getRequestBody();
 }


 public function getRequestBody(){

    if(empty($_POST) || $_POST == NULL ){
       $input = file_get_contents('php://input');
       $data = json_decode($input);
       $this->body = $data;
    }else{
       $this->body = (object) $_POST;
       $this->files = (object) $_FILES;
    }

 }
 

 function hasFiles():bool{
    $keys = array_keys($_FILES);
    $file_count = 0;
    foreach($keys as $key){
      if(!empty($_FILES[$key]['name'])){
         $file_count++;
      }
    }
   return ($file_count > 0); 
 }


function getRequestMethod(){
   $method = htmlspecialchars_decode($_SERVER['REQUEST_METHOD']);
   if(in_array(strtoupper($method), ['POST' , 'GET' , 'PUT' , 'PATCH' , 'DELETE' , 'HEAD' , 'CONNECT'])) return $method;
   throw new RuntimeException('Invalid Request method');
}


 function get_http_header(string $header_name){
   $header = str_starts_with("HTTP_", strtoupper($header_name)) ? $header_name : "HTTP_".strtoupper(trim($header_name));
   return isset($_SERVER[$header])? $_SERVER[$header] : null;
 }


}