<?php

namespace  core\aggregates\services;

use core\aggregates\account;
use core\aggregates\applicable;
use core\aggregates\cf_value;
use core\aggregates\custom_field;
use core\aggregates\field;
use core\aggregates\manager;
use core\aggregates\notification;
use core\aggregates\on_boarding_rule;
use core\aggregates\property;
use core\aggregates\renting_mode;
use core\aggregates\renting_mode_setting;
use core\aggregates\tenant;
use core\aggregates\unit;
use core\exceptions\ServiceException;
use infrastructure\database\specifications\Repositories\SettingsRepository;
use infrastructure\database\specifications\Repository;
use RuntimeException;
use web\libs\Request;

class SettingsService extends _BaseService {

    public SettingsRepository $settingsRepository;

    public function __construct()
    {
        parent::__construct();

        $this->settingsRepository = new SettingsRepository();
    }


    function setCustomField(Request $request ,account $account){

        // creating custom fields on forms

            $cf = new custom_field();
            $cf->name = $request->body->name;
            $cf->field_id = $request->body->type_id;
            if($request->body->model == 'tenant') $cf->model = tenant::class;
            if($request->body->model == 'property') $cf->model = property::class;
            if($request->body->model == 'unit') $cf->model = unit::class;
            if($request->body->model == 'manager') $cf->model = manager::class;
            $cf->account_id = $account->id;
            $this->settingsRepository->Add($cf);
            $this->settingsRepository->saveChanges();
             return $this->settingsRepository->last_insert_id() ? true : ['info' => 'The fields could not be added to your '. $request->body->model. ' model' ];

    }

    function saveCustomValue($model,$id,custom_field $custom_field,$value){

        $type = $this->settingsRepository->getFieldType($custom_field->field_id);

        $cf_v = new cf_value();
        $cf_v->model = $model;
        $cf_v->model_id = $id;
        $cf_v->cf_id = $custom_field->id;

        switch($type->type){
            case 'text':
                $cf_v->value = $value;
                break;
            case  'number':
                if(!ctype_digit($value)) return false;
                $cf_v->value = $value;
            break;
            case  'date':
                $cf_v->value = $value;
             break;
            default:
            throw new RuntimeException('could not determine the type of field');
        }
        
        $this->settingsRepository->Add($cf_v);
        $this->settingsRepository->saveChanges();
        return true;

    }

    

    function allFields(){
        return $this->settingsRepository->getList(new field);
    }

    public function timePeriods(){

        $timePeriods = [
            '2 years',
            '1 year',
            '6 months',
            '3 months',
            '30 Days',
            '1 Week',
            '2 Weeks',
            '1 Day',
            '2 Days',
            '3 Days',
            '1 hour',
            '2 hours',
            '3 hours',
            '6 hours'
        ];

        return $timePeriods;
    }

    function getCustomFields($model , account $account){
        return $this->settingsRepository->getCustomFields($model ,$account);
    }


    function createRentingMode(Request $request , account $account){

        $r_mode = new renting_mode();

        $r_mode->cycle = $request->body->cycle;

        $r_mode->grace_period = $request->body->grace_period;

        $r_mode->account_id = $account->id;

        $this->settingsRepository->Add($r_mode);

        $this->settingsRepository->saveChanges();

        return ($this->settingsRepository->last_insert_id())? true : ['info' => 'Renting Mode was not created'];
    }


    function rentingModes(account $account) {

        return $this->settingsRepository->renting_modes($account);
    }

    function OnboardingSettings($account){

        return $this->settingsRepository->getOnBoardingSettings($account);
    }

    function setRentingMode(Request $request , account $account){
        try{
            $mode = $this->settingsRepository->rentingModeById($request->body->mode);

            if(is_null($mode)) throw new ServiceException("Unknown Renting Mode");

            if($mode->account_id !== $account->id) throw new ServiceException("Unknown Renting Mode");

            $modeSetting = new renting_mode_setting();

            if($request->body->model == "unit") $modeSetting->model = unit::class;

            if($request->body->model == "property") $modeSetting->model = property::class;

            $modeSetting->model_id = $request->body->model_id;

            $modeSetting->mode_id = $mode->id;

            $this->settingsRepository->Add($modeSetting);

            $this->settingsRepository->saveChanges();

            if(!$this->settingsRepository->last_insert_id()) throw new ServiceException("Mode not assigned to item");

            return true;

        }catch(ServiceException $e){ return $e->infoMessage();}

    }


    public function switchMode(Request $request , $account){
        try{

        $setting = $this->settingsRepository->rentingModeSettingById($request->body->setting_id);
        
        if(is_null($setting)) throw new ServiceException("Unknown Setting Reference");

        $mode = $this->settingsRepository->rentingModeById($request->body->mode);

        if(is_null($mode)) throw new ServiceException("Unknown Renting Mode");

        if($mode->account_id !== $account->id) throw new ServiceException("Unknown Renting Mode");

        $setting->mode_id = $mode->id;

        $this->settingsRepository->Add($setting);

        $this->settingsRepository->saveChanges();

        if(!$this->settingsRepository->rows_affected()) throw new ServiceException("Mode not assigned to item");

        return true;

        }catch(ServiceException $e){ return $e->infoMessage();}
    }




}