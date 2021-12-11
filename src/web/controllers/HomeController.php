<?php
namespace web\controllers;

use kernel\controller;

class HomeController extends _BaseController implements controller {
   
    public function __construct()
    {
        parent::__construct();
    }
    // all controllers must have an index method
    function  index(){
        return _view('Home');
    }


}