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

        return _view('pages.Properties',['custom_property_fields' => $this->SettingsService->settingsRepository->getCustomFields(property::class,$this->account) ] );
    }

    public function tenants(){
        return _view('pages.Tenants');
    }

    public function manage_property($id){

        $data = $this->PropertyService->repository->getById($id , $this->account);
        $addon_types = $this->PropertyService->addOnTypes();
        $custom_tenant_fields = $this->SettingsService->getCustomFields(tenant::class,$this->account);
        $onboarding_rules = $this->SettingsService->settingsRepository->getOnBoardingRules($this->account);
        if(is_null($data)) _redirect('properties');
        return _view('pages.mgt.Manage_Property',['property' => $data , "add_on_types" => $addon_types , "custom_tenant_fields" => $custom_tenant_fields , 'onboarding_rules' => $onboarding_rules]);
    }


    public function settings(){
        return _view('pages.Settings',['fields' => $this->SettingsService->allFields() , 'periods' => $this->SettingsService->timePeriods() , 'enforcements' => $this->SettingsService->enforcements() ]);
    }
}