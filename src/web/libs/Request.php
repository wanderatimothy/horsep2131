<?php
namespace web\libs;

class Request {

    public $body;

    function __construct()
    {
        $this->body = $this->get_body();
    }

    private function get_body(){
        $this->method = $_SERVER['REQUEST_METHOD'];
        if(strtoupper($this->method) == 'GET'){
         $this->body = null;
         return;
        }
        if(strtoupper($this->method) == 'POST'){
            $this->body = (object) $_POST;
            return;
        }else{
            $data =  file_get_contents('php://input');
            $this->body = json_decode($data);
        }

        $this->files = $_FILES;

    }

    function get_http_header(string $header){
        $h = strtoupper('http_'.$header);
        return (array_key_exists($h , $_SERVER)) ? $_SERVER[$h] : null;
    }


    function has_files(){
        return (count($_FILES)  >  0 );
    }







}