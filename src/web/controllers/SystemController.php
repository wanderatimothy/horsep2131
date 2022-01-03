<?php

namespace web\controllers;

use core\aggregates\property;
use core\aggregates\services\AuthService;
use core\aggregates\services\PropertyService;
use core\aggregates\services\SettingsService;
use core\aggregates\services\TokenService;
use core\aggregates\tenant;
use kernel\controller;
use web\libs\Session;
use web\models\propertiesPageViewModel;
use web\models\propertyViewModel;
use web\models\UnitViewModel;

class SystemController extends _BaseController implements controller{

    private $PropertyService;

    private $SettingsService;

    private AuthService $accountService;

    private $account;

    private $claims;

    public function __construct()
    {
        parent::__construct();

        $this->PropertyService = new PropertyService();
        $this->SettingsService = new SettingsService();

        $tokenService = new TokenService();

        if(is_null(Session::get('_TOKEN'))) _redirect('login');

        $claims = $tokenService->validate(Session::get('_TOKEN'));

        if($claims == false)_redirect('login');

        $this->claims = (object) $claims;
        
        $this->accountService = new AuthService();

        $this->account = $this->accountService->accountDetails($this->claims->user_id);
        
    }

    public function logout(){

        $this->accountService->deleteAuthToken($this->claims->email);
        Session::_clear('_TOKEN');
        Session::_clear('_AUTHENTICATED');
        return _redirect('login');

    }

    public function index()
    {
        return _view('pages.Dashboard');
    }

    public function properties(){

        $vm = new propertiesPageViewModel();

        return _view('pages.Properties',["vm" => $vm] );
    }

    public function tenants(){
        return _view('pages.Tenants');
    }

    public function manage_property($id){

        $data = $this->PropertyService->repository->getById($id , $this->account);

        if(is_null($data)) _redirect('properties');

        $vm = new propertyViewModel($data,$this->account);

        return _view('pages.mgt.Manage_Property',["vm" => $vm]);
    }

    public function manage_unit($property,$unit){

        $property = $this->PropertyService->repository->getById($property , $this->account);

        if(is_null($property)) return _view('pages.mgt.Not_Found',['message' => 'Unit does not exist !']);

        $unit_model = $this->PropertyService->repository->getUnit($unit);

        if(is_null($unit_model)) return _view('pages.mgt.Not_Found',['message' => 'Unit does not exist !']);
        
        if($unit_model->property_id != $property->id) return _view('pages.mgt.Not_Found',['message' => 'Unit does not exist !']);

        $vm = new UnitViewModel($unit_model , $property , $this->PropertyService );

        return _view('pages.mgt.Manage_Unit',["vm" => $vm]);
    }



}