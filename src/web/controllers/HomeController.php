<?php
namespace web\controllers;

use infrastructure\database\migrations\migrator;
use infrastructure\database\seeders\db_seeder;
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

    function login(){
        return _view('Login');
    }

    function register(){
        return _view('Register');
    }

    function migrate(){
        migrator::migrate();
    }

    public function seed(){
        db_seeder::seedData();
    }

    


}