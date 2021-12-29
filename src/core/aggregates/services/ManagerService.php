<?php

namespace core\aggregates\services;

use core\aggregates\account;
use core\aggregates\managed_entity;
use core\aggregates\manager;
use core\aggregates\property;
use core\exceptions\ServiceException;
use GuzzleHttp\Exception\ServerException;
use infrastructure\database\specifications\Repositories\ManagerRepository;
use web\libs\Request;

class ManagerService extends _BaseService {

    public ManagerRepository $repository;

    private PropertyService  $propertyService;

    private TokenService $tokenService;

    private TenantService $tenantService;

    public function __construct()
    {
        parent::__construct();        

        $this->repository = new ManagerRepository();

        $this->tokenService = new TokenService();

        $this->propertyService = new PropertyService();

        $this->tenantService = new TenantService();

        
    }


    public function create_manager(Request $request , account $account){

        try{

            if(!is_null($this->repository->account($request->body->email ,$account))) throw new ServiceException("Account already exists");
            $valid_contact = PhoneService::phoneNumberIsValid($request->body->managers_contact);
            if($valid_contact == false) throw new ServiceException("Invalid Phone Number ");
            if($this->repository->contactAlreadyRegistered($valid_contact,$account)) return ['managers_contact' => "Contact is already registered to another manager"];
            $manager = new manager();
            $manager->name = strtoupper($request->body->managers_name);
            $manager->contact = $valid_contact;
            $manager->email = $request->body->managers_email;
            // initial account password is the phone number registered with the manager
            $manager->password = password_hash($request->body->contact , PASSWORD_DEFAULT);
            // company issued identification number
            $manager->id_number = $request->body->identity_number;

            if($request->hasFiles()){
                $photo_upload = $this->fileUploadService->uploadSingleImage((object)$request->files->manager_photo , "managers/". str_replace("",$account->id, strtolower($request->body->name).time()));

                if(!$photo_upload) throw new ServiceException("Managers Photo could not be saved" , ["message" => $this->fileUploadService->errors[0]]);

                $manager->manager_photo = $this->fileUploadService->uploadedFiles[0];
            }

            $manager->account_id = $account->id;;
            $this->repository->Add($manager);
            $this->repository->saveChanges();

            if($this->repository->last_insert_id()) return true ;

            return  ['info' => "Manager was not added the system"];


        }catch(ServiceException $e){

            return $e->infoMessage();
        }

    }

    public function authManager (Request $request , account $account){
        
        $Manager = $this->repository->account($request->body->email , $account);

        if(is_null($Manager)) return [ 'info' =>'Account does not exist'];

        if(!password_verify($request->body,$Manager->password)) return ["Invalid Credentials"];
        
        if($Manager->is_suspended) return [ "info" =>'Account has been suspended contact your superiors'];


        $claims = ["is_manager" => true  , "is_admin" => false  , "account" => $account->id , "manager_id" => $Manager->id];

        $token = $this->tokenService->generate($claims);

        /**
         *  on manager login
         * 
         * */ 
        
        $this->notificationService->sendNotification('on_manager_login',array(
            "template_data" => array(
                'manager_name' => $Manager->name,
                'timestamps' => DateService::current_timestamp()
            ),
            "_config_" => array(
                'recipient_account_type' => account::class,
                'recipient_id' => $account->id,
            )
        ));

        return ['authenticated' => true , "token" =>$token];

    }


    public function resetManagersPassword(Request $request , account $account){

        try{

            $manager = $this->repository->getManager($request->body->manager_id , $account);

            if(is_null($manager)) throw new ServiceException("Unknown Manager Reference");

            if(strcmp($request->body->new_password , $manager->password) == 0) throw new ServiceException("Password is the old password");

            $manager->password = password_hash($request->body->new_password , PASSWORD_DEFAULT);

            $this->repository->Add($manager);

            $this->repository->saveChanges();

            return $this->repository->rows_affected();

        }catch(ServiceException $e) { return $e->infoMessage();}


    }


   public function assignToProperty(Request $request ,account $account){
    try{
    if(!in_array($request->body->role ,$this->assignableTasks())) return ["info" => "Undefined Role"];

     $manager = $this->repository->getManager($request->body->manager_id , $account);

     if(is_null($manager)) throw  new ServiceException("UnKnown Manager Reference"); 
     
     $property = $this->propertyService->repository->getById($request->body->property_id , $account);

     if(is_null($property)) throw new ServiceException("Unknown Property Reference");
      
     $extras = array();

     if(isset($request->body->start_date)) $extras['start_date'] = $request->body->start_date;

     if(isset($request->body->end_date)) $extras['end_date'] = $request->body->end_date;

     $assigned = $this->createAssignMent(property::class,$request->body->property_id,$request->body->role,$manager,$extras);
     
     if(!$assigned) throw new ServiceException("manager was not assigned to the property");

     $this->notificationService->sendNotification('new_role_assignment',array(
        "template_data" => array(
            'property' => $property->property_label,
            'role' => $request->body->role,
            'timestamps' => DateService::current_timestamp()
        ),
        "_config_" => array(
            'recipient_account_type' => manager::class,
            'recipient_id' => $manager->id,
        )
     ));
     
     return true;


    }catch(ServiceException $e){
        return $e->infoMessage();
    }

   }


   public function createAssignMent(string $model, int $model_id , string $role , manager $manager , array $extras){
    
    if(!in_array($role ,$this->assignableTasks())) return ["info" => "Undefined Role"];
    
    $assignment = new managed_entity();
    $assignment->managers_id = $manager->id;
    $assignment->model = $model;
    $assignment->model_id = $model_id;
    $assignment->managed_as = $role;
    $assignment->starting_from = isset($extras['start_date']) ? $extras['start_date'] : DateService::current_timestamp();
    if (isset($extras['end_date'])) $assignment->ending = $extras['end_date'];
    $this->repository->Add($assignment);
    $this->repository->saveChanges();

    return $this->repository->last_insert_id() ? true : false;
   }
   public function assignableTasks () :array {
       return [
           "Rent Collector",
           "Bill Collector",
           "Service Fee Collector",
           "Care Taker",
           "Supervisor",
           "Auditor"
       ];
   }

   public function assignedProperties(manager $manager ,$role = "Supervisor"){

    if(!in_array($role ,$this->assignableTasks())) return [];

     $account = $this->accountRepository->findById($manager->id);
 
     if(is_null($account)) return [];

     $managed_entities =  $this->repository->getAssignments(property::class,$role,$manager);

     if(empty($managed_entities)) return [];

     $ids = [];

     foreach ($managed_entities as $entity) $ids[] = $entity["id"];

     $properties = $this->propertyService->repository->getById($ids,$account);
    
     return $properties;

   }


   public function assignToUnits(Request $request , account $account){
       try{
        $manager = $this->repository->getManager($request->body->manager_id , $account);

        if(is_null($manager)) throw  new ServiceException("UnKnown Manager Reference"); 
        
        if(empty($request->body->units)) throw  new ServiceException("Unknown unit reference");
        // validate unit belongs to account
        if(!in_array($request->body->role ,$this->assignableTasks())) throw new ServiceException("Unknown Role reference");

        $extras = array();

        if(isset($request->body->start_date)) $extras['start_date'] = $request->body->start_date;

        if(isset($request->body->end_date)) $extras['end_date'] = $request->body->end_date;

        $assigned = [];

        foreach($request->body->units as $unit){

            $id =  $this->createAssignMent(unit::class , $unit , $request->body->role , $manager ,$extras);

            $assigned[] = $id;
        }

        if(count($request->body->units) != count($assigned)) throw new ServiceException("Manager was not assigned");

        $this->notificationService->sendNotification('new_role_assignment_units',array(
            "template_data" => array(
                'role' => $request->body->role,
                'timestamps' => DateService::current_timestamp()
            ),
            "_config_" => array(
                'recipient_account_type' => manager::class,
                'recipient_id' => $manager->id,
            )
         ));
        
         return true;

       }catch(ServiceException $e){
           return $e->infoMessage();
       }
   }

   function getAssignedUnits(manager $manager , $role = "Care Taker"){

    if(!in_array($role ,$this->assignableTasks())) return [];

    $account = $this->accountRepository->findById($manager->id);
 
    if(is_null($account)) return ['Unknown Account reference'];

    $managed_entities =  $this->repository->getAssignments(unit::class,$role,$manager);

    if(empty($managed_entities)) return [];

    $ids = [];

    foreach ($managed_entities as $entity) $ids[] = $entity["id"];

    return  $this->repository->getManagedUnits($ids,0);

   }



   function getTenantsOnManagerAssignedProperties(manager $manager , $role = "Supervisor"){

    $properties = $this->assignedProperties($manager,$role);

    if(empty($properties)) return [];

    $ids = [];

    foreach ($properties as $entity) $ids[] = $entity->id;

    return $this->tenantService->repository->tenantsOnProperty($ids,0);

   }



   function deleteManager(manager $manager){

        $manager->is_deleted = 1;

        $this->repository->Add($manager);

        $this->repository->saveChanges();

        $this->fileUploadService->deleteUpload($manager->manager_photo);

        $this->repository->revokeAllAssignments($manager);

        if($this->repository->rows_affected()) return true;

       return [ "info" => 'Manager was not been deleted'];

   }

   function revokeRole(string $model, int $id  , manager $manager , $role){

       if(!in_array($role ,$this->assignableTasks())) return false;

       $role_assignment = $this->repository->assignment($model , $id , $manager , $role);

       $role_assignment->revoked = true;

       $role_assignment->ending =  DateService::current_timestamp();

       $this->repository->Add($role_assignment);

       $this->repository->saveChanges();

       
       $this->notificationService->sendNotification('on_role_revoked',array(
        "template_data" => array(
            'role' => $role,
            'timestamps' => DateService::current_timestamp()
        ),
        "_config_" => array(
            'recipient_account_type' => manager::class,
            'recipient_id' => $manager->id,
        )
     ));

       return ($this->repository->rows_affected());
   }
}