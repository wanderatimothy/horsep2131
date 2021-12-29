<?php

use web\libs\Session;

function _template(string $name , array $data = [] , callable $before = null ){
    $source = str_replace('.',DIRECTORY_SEPARATOR,$name).'_html'.extension;
    $viewData = !is_null($before) ? call_user_func($before,$name,$data ) : [];
    if(str_starts_with(basename($source) , '_')){
       if(file_exists( __VIEWS__DIR__ .'shared/'.$source) ) {
        include __VIEWS__DIR__. 'shared/'.$source;
        return true;
      }else{
        include __VIEWS__DIR__. 'shared/errors/_template_not_found'.'_html'.extension;
        return false;  
      }
    }
    if(file_exists(__VIEWS__DIR__ . $source)){

        include __VIEWS__DIR__.$source;
        return true;

    }else{
        include __VIEWS__DIR__. 'shared/errors/_view_not_found'.'_html'.extension;  

        return false;
    }

}

function _view(string $name , array $data = [ ] , string $layout = '_Layout'){
    $props = ["title" => app_name ,"_layout" => $layout ,"view" => $name , "data" => $data];
    _template($layout ,$props  , function($name , $data) {
        return ['view_name' => $data['view']  , 'view_data' => $data['data']];
    });
    Session::clearTempData();
}

function _asset(string $name ){
    return app_url(__ASSETS__DIR__.$name);
}

function respondWithJson($data,$headers = [],int $status ){
    $response = json_encode(['data' => $data]);
    foreach($headers as $h){ header($h);}
    http_response_code($status);
    echo $response;
}

function debug_dump(array $variables){
    echo "<pre>";
    call_user_func_array('var_dump',$variables);
    echo "<pre>";
    die;
}

function app_url(string $name , array $params = null){
    return base_url . ltrim($name,'/'). ( !is_null($params) ?  implode('/' , $params) : '');
}

function from_disk(string $name){
    return app_url('').__APP_STORAGE__DIR.ltrim($name,'/');
}

function _redirect(string $url){
    header('location:'.app_url($url));
}
if (!function_exists('trigger_deprecation')) {
    
    function trigger_deprecation(string $package, string $version, string $message, ...$args): void
    {
        @trigger_error(($package || $version ? "Since $package $version: " : '').($args ? vsprintf($message, $args) : $message), \E_USER_DEPRECATED);
    }
}