<?php

use kernel\App;

define('EXTENSION' , '.php');
define ('APPDIR', 'src/');
define ('WEBDIR', 'web/');

spl_autoload_register(function($resource){
 $resource_path =  APPDIR.str_replace('\\' , DIRECTORY_SEPARATOR,$resource ).EXTENSION;
 if(file_exists($resource_path)) require_once $resource_path;
});
$app = new App;
$app->init('clean.architecture');

