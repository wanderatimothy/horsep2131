<?php
namespace web\controllers\resources;

use core\aggregates\property;
use core\aggregates\services\SettingsService;
use core\aggregates\services\ValidationService;
use core\aggregates\tenant;

class SettingsResource extends _BaseResource {

    public SettingsService $settingService;

    public function __construct()
    {
        parent::__construct();

        $this->settingService = new SettingsService();
    }


    public function create_rent_rule(){
        $validations = ValidationService::rentRuleForm($this->request);

        if(count($validations) == 0) {
            $create_rule = $this->settingService->createRentRule($this->request,$this->account);

            if($create_rule == true)    return respondWithJson(['errors' => [] , 'success' => true ] , ['content-type: application/json'] , 201);
    
            if(is_array($create_rule))  return respondWithJson(['errors' => $create_rule , 'success' => false ] , ['content-type: application/json'] , 422);
        }
        return respondWithJson(['errors' => $validations , 'success' => false ] , ['content-type: application/json'] , 422);

    }


    public function get_all_rent_rules(){

        $data = $this->settingService->settingsRepository->getRentRules($this->account);
        return respondWithJson($data , ['content-type:application/json'] ,200);

    }

    public function create_field(){
        if(!ValidationService::not_undefined('name',$this->request->body)) return respondWithJson(['errors' => ['name' => 'name of the field is required'] , 'success' => false ] , ['content-type: application/json'] , 422);
        $created = $this->settingService->setCustomField($this->request , $this->account);
        if($created == true)    return respondWithJson(['errors' => [] , 'success' => true ] , ['content-type: application/json'] , 201);
        if(is_array($created))  return respondWithJson(['errors' => $created , 'success' => false ] , ['content-type: application/json'] , 500);
    }
    
    public function customTenantsField(){
        $data = $this->settingService->getCustomFields(tenant::class,$this->account);
        return respondWithJson($data , ['content-type:application/json'] ,200);
    }
    public function customPropertyField(){
        $data = $this->settingService->getCustomFields(property::class,$this->account);
        return respondWithJson($data , ['content-type:application/json'] ,200);
    }

    public function create_on_boarding_rule(){
        $error = [];
        if(!ValidationService::not_undefined('rule_title' , $this->request->body)) $error['o_rule_title'] = 'Rule Title is required';
        if(!ValidationService::not_undefined('rule_description' , $this->request->body)) $error['rule_description'] = 'Definition is required';
        if(count($error) > 0) return respondWithJson(['errors' => $error , 'success' => false ] , ['content-type: application/json'] , 422);
        $created = $this->settingService->create_on_boarding_rule($this->request); if($created == true)    return respondWithJson(['errors' => [] , 'success' => true ] , ['content-type: application/json'] , 201);
        if(is_array($created))  return respondWithJson(['errors' => $created , 'success' => false ] , ['content-type: application/json'] , 500);
    }

    public function onboardingRules(){

        $data = $this->settingService->getOnBoardingRules();
        return respondWithJson($data , ['content-type:application/json'] ,200);

    }
}