<?php
namespace web\controllers;

use core\aggregates\services\AuthService;
use core\aggregates\services\ValidationService;
use kernel\controller;
use web\libs\Session;

class AuthController extends _BaseController implements controller {

    public $authService;
   
    public function __construct()
    {
        $this->authService = new AuthService();
        
        parent::__construct();
    }
    // all controllers must have an index method
    function  index(){}

    function handle_user_registration(){

            $validation = ValidationService::UserForm($this->request); 

            if(!empty($validation)) Session::error($validation);  _redirect('register');

            if($this->authService->emailExist($this->request->body->email))Session::info(['failed' => 'Email already exists !']); _redirect('register');

            $created = $this->authService->createUser($this->request);

            if($created == true) Session::info(["success" => "your account has been created successfully use <a class='btn btn-sm btn-info' href='".app_url('login')."'>login</a> "]);
                
            if(is_array($created))Session::info(["failed" => $created['info']]);
             
             _redirect('register');


    }
    
    function handle_user_authentication(){

           $validation = ValidationService::LoginForm($this->request);

           if(!empty($validation)) Session::error($validation);  _redirect('login');

           if(!$this->authService->emailExist($this->request->body->email)) Session::info(['failed' => 'Sorry! your email is not associated with any account.']); _redirect('login');
          
           $authResult = $this->authService->authenticate($this->request);

           if(is_null($authResult))  Session::info(['failed' => 'Invalid Credentials Access Denied!']); _redirect('login');

           if(array_key_exists('user_is_banned',$authResult)) Session::info(['failed' => 'Access Denied! account has been banned ']); _redirect('login');
          
           Session::set('_TOKEN',$authResult['token']);

           Session::set('_AUTHENTICATED',$authResult['authenticated']);

           return _redirect('dashboard');

    }





}