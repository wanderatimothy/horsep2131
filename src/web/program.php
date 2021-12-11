<?php

use web\libs\Router;

define('assets_dir' , 'wwwroot/');
define('view_dir' , 'views/');
// configure the base url to point to your application
define('base_url','http://127.0.0.1/clean/');
$router = new Router;
// creating navigational url helper
function app_url(string $name , array $params = null){
 return base_url . ltrim($name,'/'). ( !is_null($params) ?  implode('/' , $params) : '');
}
// register the router for the web application endpoints and apis
// define your view engine
function _template(string $name , array $data = [''] , callable $before = null , callable $after = null){
    $view_resource =  APPDIR.WEBDIR.view_dir.str_replace('.',DIRECTORY_SEPARATOR,$name).'_html'.EXTENSION;
    

    
    if(file_exists($view_resource)){
      if(!is_null($before)) call_user_func_array($before , $data);
      extract($data);
      include $view_resource;
      if(!is_null($after)) call_user_func_array($after , $data);
    }else{
        include APPDIR.WEBDIR.view_dir. 'shared/errors/_template_not_found'.EXTENSION;
    }
}
function _view(string $name , array $data = ['title' => 'app'] , callable $before = null ){
    include_once APPDIR.WEBDIR.view_dir.'shared/_Layout_html'.EXTENSION;
}

// define your api response 

function respondWithJson($data,$headers = [],int $status ){
    $response = json_encode($data);
    foreach($headers as $h){  header($h);}
    http_response_code($status);
    echo $response;
}

function _asset(string $name ){
    return base_url.APPDIR.WEBDIR.assets_dir.$name;
}
// start the application session
session_start();
// session vars
$_SESSION['TempData'] = [];

function set_temp_data($key , $value){
    $_SESSION['TempData'][$key] = $value;
}
function temp_data($key){
    return array_key_exists($key,$_SESSION['TempData'])? $_SESSION['TempData'][$key] :null;
}

$this->register(function($router){
 
 require_once  APPDIR.WEBDIR. 'routes_register'.EXTENSION;
 $router->run();
},[$router]);



