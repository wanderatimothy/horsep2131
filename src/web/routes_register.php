<?php

use core\pluralize;

$router->get('/','web\controllers\HomeController@index');

$router->set404(function(){
    echo '404';
});