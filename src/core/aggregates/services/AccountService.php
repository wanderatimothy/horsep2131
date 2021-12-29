<?php
namespace core\aggregates\services;

use core\aggregates\account;
use core\exceptions\ServiceException;
use GuzzleHttp\Exception\ServerException;
use web\libs\Request;

class AccountService extends _BaseService{


    
    function __construct()
    {
        parent::__construct();
    }


    function updateAccountDetails(Request $request, account $account){
        try{
        
        $account->business_name = $request->body->business_name;
        $account->business_address = $request->body->business_address;
        $account->business_email= $request->body->business_email;
        $account->business_contact = $request->body->business_contact;
        $account->website_url =  urldecode($request->body->website_url);
        if($request->hasFiles()){

            $upload = $this->fileUploadService->uploadSingleImage( (object) $request->files->business_logo , 'logos/'.str_replace(' ','@_@',$account->business_name));
            if(!$upload) throw new ServiceException(ServiceException::FILE_NOT_SAVED,["message" => $this->fileUploadService->errors[0]]);
            $account->business_logo = $this->fileUploadService->uploadedFiles[0];
        }
        
        $this->accountRepository->Add($account);
        $this->accountRepository->saveChanges();

        if($this->accountRepository->rows_affected()){
            return true;
        }else{
            throw new ServiceException(ServiceException::ACCOUNT_DETAILS_NOT_CHANGED);
        }

        }catch(ServiceException $e){
            return $e->infoMessage();
        }
        


        
        
    }

}