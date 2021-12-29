<?php

use web\libs\Router;
use web\libs\Session;

define('base_url','http://127.0.0.1/'.app_name .'/');

$this->load_resources(['src/web/libs/helpers/constants' . extension , 'src/web/libs/helpers/functions'.extension]);

Session::initialize();

$this->register(function($router){ require_once __ROUTE_REGISTER__; $router->run(); },[new Router]);











