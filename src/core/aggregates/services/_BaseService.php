<?php

namespace core\aggregates\services;

use core\aggregates\account;
use core\aggregates\custom_field;
use infrastructure\database\specifications\Repositories\AccountRepository;
use infrastructure\database\specifications\Repository;

class _BaseService {

    protected Repository $account_repo;

    protected  $fileUploadService;

    protected NotificationService $notificationService;
    
    public function __construct()
    {
        $this->account_repo = new Repository(account::class);

        $this->fileUploadService = new FileUploadService;
        
        $this->notificationService = new NotificationService();
    }


    protected function saveCustomField($data){

        if(!in_array($data->type,['Text','Phone','Email','Number','Description'])) return ['info'=>'Unknown Field Type'];

        $custom_field = new custom_field;

        $custom_field->name = $data->name;

        $custom_field->type = $data->type;

        $custom_field->model = $data->model;

        $custom_field->model_id = $data->id;

        switch($data->type){

            case 'Text' :

                if (strlen($data->value) < 1 && !ctype_alnum($data->value))   return ['info' => 'please enter a valid' .$data->name ];

                $custom_field->value = $data->value;
               
                break;

            case 'Phone': 

                $phone = PhoneService::phoneNumberIsValid($data->value);

                if($phone == false)  return ['info' => 'please enter a valid' .$data->name ];

                $custom_field->value = $phone;

                break;

            case 'Number':

                if(!ctype_digit($data->value)) return ['info' => $data->name . ' is not a number' ];

                $custom_field->value = $data->value;

                break;
            
            case 'Email':

                $email = filter_var($data->value,FILTER_VALIDATE_EMAIL);

                if($email == false)  return ['info' => $data->name . 'is not a valid Email' ];

                $custom_field->value = $email;
            
                break;

            default: 

                 $custom_field->value = $data->value;

             break;
        }

        $repository =  new Repository(custom_field::class);

        $repository->Add($custom_field);

        $repository->saveChanges();

        return $repository->last_insert_id();

    }
 

}