<?php
namespace core\aggregates\services;

use core\aggregates\account;
use core\aggregates\landlord;
use core\aggregates\notification;
use core\aggregates\token;
use core\aggregates\user;
use core\aggregates\wallet;
use infrastructure\database\specifications\Repositories\AccountRepository;
use infrastructure\database\specifications\Repositories\UserRepository;
use web\libs\Request;

class AuthService{

   public UserRepository $_repository;
   public AccountRepository $accountRepository;
   public TokenService $tokenService;
   public function __construct()
   {
       $this->_repository = new UserRepository();
       $this->accountRepository = new AccountRepository();
       $this->tokenService = new TokenService();
   }

   public function createUser(Request $request){
       $user = new user();
       $user->email = $request->body->email;
       $user->password_hash = password_hash($request->body->password,PASSWORD_DEFAULT);
       $user->contact = $request->body->contact;
       $this->_repository->Add($user);
       $this->_repository->saveChanges();
       if($this->_repository->last_insert_id()){
        //user created
        $user_id = $this->_repository->last_insert_id();
        $account  = new account();
        $account->user_id = $user_id;
        $account->subscription_type_id = 1;
        $account->business_contact = $request->body->contact;
        $landlord = new landlord();
        $landlord->names = "YOUR ACCOUNT";
        $landlord->email = $user->email;
        $landlord->contact = $user->contact;
        $landlord->user_id = $user_id;
        // creating user_account , person landlord account and wallet;
        $this->accountRepository->Add($account);
        $this->accountRepository->Add($landlord);
        $this->accountRepository->saveChanges();

        $account_details = $this->accountDetails($user_id);
        $wallet = new wallet();
        $notification = new notification();
        $notification->account_id = $account_details->id;
        $wallet->account_id = $account_details->id;
        unset($account_details);
        $this->accountRepository->Add($wallet);
        $this->accountRepository->Add($notification);
        $this->accountRepository->saveChanges();

        return true;
       }else{
          return ['info' => "your account was not created"];
       }
       

   }


   public function emailExist($email){
    
     return $this->_repository->emailExists($email);
    
   }

   public function  authenticate(Request $request){
        // can authenticate
        $user_object = $this->_repository->getUserByEmail($request->body->email);
        if(password_verify($request->body->password , $user_object->password_hash)){
          // delete redundant tokens
          if(!$user_object->allow_multiple_sessions) $this->_repository->deleteRedundantTokens($user_object->id);
          if($user_object->is_banned) return ["token" => null ,'authenticated' => false , 'user_is_banned' => true ];
          $accounts_info = $this->accountDetails($user_object->id);
          $claims = ['user_id' => $user_object->id, 'email' => $user_object->email ,'is_admin' => true , 'account' => $accounts_info->id ];
          $token  = $this->tokenService->generate($claims);   
          $tokenSecured =  new token();
          $tokenSecured->token_hash = password_hash($token,PASSWORD_DEFAULT);
          $tokenSecured->user_id = $user_object->id;
          $this->_repository->Add($tokenSecured);
          $this->_repository->saveChanges();
          return ['token' => $token ,  'authenticated' => true];
        }else{
          return null;
        }

   }


   
   public function deleteAuthToken($email_from_claim){
     $user = $this->_repository->getUserByEmail($email_from_claim);
     if(!$user->allow_multiple_sessions) $this->_repository->deleteRedundantTokens($user->id);
   }


   function update($user){
       $this->_repository->Add($user);
   }


   function delete($user){
       $this->_repository->Add($user);
       $this->_repository->delete();
   }


   function accountDetails($user_id){
      return $this->accountRepository->user_account_details($user_id); 
   }

   function save(){
     return $this->_repository->saveChanges();
   }



}