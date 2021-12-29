<?php

namespace core\aggregates\services;

use core\aggregates\account;
use core\aggregates\add_on_type;
use core\aggregates\floor;
use core\aggregates\landlord;
use core\aggregates\property;
use core\aggregates\property_type;
use core\aggregates\unit;
use core\aggregates\unit_addon;
use Exception;
use infrastructure\database\specifications\Repositories\LandlordRepository;
use infrastructure\database\specifications\Repositories\PropertyRepository;
// use infrastructure\database\specifications\Repository;
use infrastructure\logger\Logger;
use web\libs\Request;

class PropertyService extends _BaseService {

    public PropertyRepository $repository;

    public LandlordRepository $landlordRepository;

    private SettingsService $settingService;


    public Logger $_logger;

    public function __construct(){

        parent::__construct();
        $this->repository = new PropertyRepository();
        $this->landlordRepository = new LandlordRepository();
        $this->settingService = new SettingsService();

        // $this->_logger = new Logger('accounts_service_logs.log','accounts_log');
        
    }


    function create_landlord(Request $request , account $account ){
        
        
        $subscription_plan = $this->accountRepository->subscriptionType($account);
        
        $no_of_landlords = $this->accountRepository->numberOfLandlords($account);

        try{
            if(is_null($subscription_plan)) throw new Exception("Issue:: Cannot have an account without a subscription type.");

            if($no_of_landlords < $subscription_plan->landlords_allowed){
                
                $landlord = new landlord();
                $landlord->names = strtoupper($request->body->names);
                $landlord->email = $request->body->email;
                $landlord->contact = $request->body->contact;
                $landlord->managers_commission = $request->body->commission;
                $landlord->tenants = $request->body->tenants;
                $landlord->last_payout = 'NULL';
                $landlord->amount_expected = floatval($request->body->collection_expected);
                $landlord->user_id = $account->user_id;

                $this->landlordRepository->add($landlord);
                $this->landlordRepository->saveChanges();

                $account->landlords = $account->landlords + 1;
                $this->accountRepository->Add($account);
                $this->accountRepository->saveChanges();

                return true;   
            }
            
            return ['error' => 'Your package is limited to' . $subscription_plan->landlords_allowed. ' landlords to add more please upgrade to a larger plan '];


        }catch(Exception $e){
            // $this->_logger->log()->warning($e->getMessage());
            return false;
        }
       
    }



    function create_property(Request $request , account $account){
         $subscription_plan = $this->accountRepository->subscriptionType($account);
         $no_of_properties = $this->landlordRepository->numberOfProperties(implode(',',$this->get_landlord_ids($account)));
         try{
            if(is_null($subscription_plan)) throw new Exception("Issue:: Cannot have an account without a subscription type.");

            if($no_of_properties < $subscription_plan->properties_allowed){
              
                $property = new property();
                $property->landlord_id = $request->body->landlord_id;
                $property->property_label = $request->body->label;
                $property->location = $request->body->location;
                $property->rent_amount = $request->body->rent_amount;
                $property->type_id = $request->body->type;
                $property->has_units = $request->body->has_units;

                $this->repository->Add($property);
                $this->repository->saveChanges();

                if(isset($request->body->has_custom_fields)){
                 $fields = $this->settingService->getCustomFields(property::class , $account);
                 $values = get_object_vars($request->body);
                 foreach($fields as $field){
                     $this->settingService->saveCustomValue(property::class , $this->repository->last_insert_id() , $field, $values[strtolower(str_replace(" ","_",$field->name))]);  
                        
                    }
                }

                $account->properties = $account->properties + 1;
                $this->accountRepository->Add($account);
                $this->accountRepository->saveChanges();

                return true; 
            }

            return ['error' => 'Your package is limited to' . $subscription_plan->properties_allowed. ' properties to add more please upgrade to a larger plan '];


         }catch(Exception $e){
            // $this->_logger->log()->warning($e->getMessage());
            return false;
         }

    }




    function properties(account $account){

        $ids = $this->get_landlord_ids($account);
        return $this->landlordRepository->properties(implode(',',$ids));
        
    }

    public function getAllUnits(account $account , bool $deleted = false ){
        $ids = $this->get_property_ids($account);
        return $this->repository->getUnits($ids , $deleted);
    }
    
    function getPropertyUnits(int $id){
        return $this->repository->getUnits([$id]);
    }

    private function get_landlord_ids(account $account){
        $landlords = $this->getLandlords($account);
        if(empty($landlords))  return [];

        $ids = [];
        foreach($landlords as $landlord){
            $ids[] = $landlord->id;
        }

        return $ids;
    }
    
     function get_property_ids(account $account){
        $properties = $this->properties($account);
        if(empty ($properties)) return [];
        $ids = [];
        foreach($properties as $property){
            $ids[] = $property->id;
        }

        return $ids;
    }

    
    public function create_property_units(Request $request , account $account , property $property ){
        $subscription_plan = $this->accountRepository->subscriptionType($account);
        $landlords = $this->get_landlord_ids($account);
        $properties = $this->get_property_ids($account);
        $no_of_units = $this->numberOfUnits($properties);
        try{

            if(is_null($subscription_plan)) throw new Exception('Please subscribe to a package');

            if(empty($landlords)) throw new Exception('Cannot have a property with out a landlord');

            if(!in_array($property->landlord_id , $landlords)) throw new Exception('This property is not yours');

            if($no_of_units == $subscription_plan->units_allowed ) throw new Exception('You have reached your limit! to add more units Please upgrade your package ');


            if( isset($request->body->auto_generate)){
                // use auto generate
                $units_to_create = $request->body->number_to_create;

                $start_index = $request->body->start_index;
             }else{
                 $units_to_create = 1;

                 $start_index = null;
             }

             if(($units_to_create + $no_of_units) > $subscription_plan->units_allowed) throw new Exception("Sorry you can not exceed ".$subscription_plan->units_allowed." units. Please upgrade your package and try again!");

             $counter = 0;

             $data = [];

             while($counter < $units_to_create){
            
                $unit = new unit();

                if( isset($request->body->auto_generate)){
                    $unit->label = $request->body->prefix . " ". $start_index ++;
                }else{
                    $unit->label = $request->body->prefix;
                }
                $unit->rent_amount = $request->body->rent_amount;
                $unit->self_contained = $request->body->self_contained;
                $unit->description = $request->body->description;
                $unit->occupants_limit = $request->body->allowed_occupants;
                $unit->floor_id = $request->body->floor_id;
                $unit->rooms = $request->body->rooms;
                $unit->facilities = $request->body->facilities;
                $unit->property_id = $property->id;
                $data[] = $unit;

                $counter ++;  
             }

             
             $created_units = [];

            for($i = 0 ; $i < count($data) ; $i++){
                $this->repository->Add($data[$i]);
                $this->repository->saveChanges();
                $data[$i]->id = $this->repository->last_insert_id();
                $created_units[] = $this->repository->last_insert_id();
            }


            if(array_key_exists("addon_name", get_object_vars($request->body))){

                foreach($created_units as $id){

                    $i = 0;

                    while($i < count($request->body->addon_name)){
                        
                        $addon = new unit_addon();
                        $addon->addon_name = $request->body->addon_name[$i];
                        $addon->add_on_type_id = $request->body->addon_type_id[$i];
                        $addon->addon_cost = $request->body->addon_cost[$i];
                        $addon->addon_meter = $request->body->addon_meter[$i];
                        $addon->unit_id = $id;
                        $this->repository->Add($addon);
                        $i++;
                    }

                    $this->repository->saveChanges();

                    $addon_created[] = $this->repository->last_insert_id();
                }

                if(count($addon_created) == count($created_units) && count($created_units) == $units_to_create ){

                    return true;
                }else{
                        $this->repository->data = $data;
                        $this->repository->delete();
                        throw new Exception('Addons could not be created contact support');
                }


            }

            if(count($created_units) == $units_to_create) {
                $account->units = $account->units + $units_to_create;
                $this->accountRepository->Add($account);
                $this->accountRepository->saveChanges();
                return  true;
            }else{
                $this->repository->data = $data;
                $this->repository->delete();
                 return false;
            }



        }catch(Exception $e){

            return ['info' => $e->getMessage() ];
        }






    }



    function propertyTypes(){
        return $this->repository->getList(new property_type);
    }

    function getLandlords(account $account){
        return $this->accountRepository->landlords($account);
    }

    function floors(){
        return $this->repository->getList(new floor);
    }
    public function addOnTypes(){
        return $this->repository->getList(new add_on_type);
    }

    public function numberOfUnits($property_ids){
        return $this->repository->countUnits($property_ids);
    }

    public function deleteProperty(property $property , account $account){

        $property->is_deleted = 1;

        $this->repository->Add($property);

        $account->properties = $account->properties - 1;

        $this->repository->Add($account);
        
        $this->repository->saveChanges();

        return true;
    }
}