<?php
namespace core\aggregates\services;

use core\aggregates\user;
use Exception;
use infrastructure\database\specifications\Repositories\AccountServiceRepository;
use infrastructure\database\specifications\Repositories\UserServiceRepository;
use web\libs\Request;

class AuthService{

   private TokenService $tokenService;

   private AccountServiceRepository $accountServiceRepository;

   private UserServiceRepository $userServiceRepository;

   public function __construct()
   {
       $this->userServiceRepository = new UserServiceRepository();
       $this->accountServiceRepository = new AccountServiceRepository();
       $this->tokenService = new TokenService();
   }

   public function createUser(Request $request){
    try{
       $user = new user;
       $user->email = $request->body->email;
       $user->password_hash = password_hash($request->body->password,PASSWORD_DEFAULT);
       $user->contact = $request->body->contact;

       $this->userServiceRepository->user_repo->addToQue($user);
       $this->userServiceRepository->user_repo->saveChanges();

       if(!$this->userServiceRepository->user_repo->last_insert_id()) throw new Exception('Failed to create user account');

       $user->id = $this->userServiceRepository->user_repo->last_insert_id();

       if(!$this->accountServiceRepository->create_account($user)) throw new Exception("Account was not created");
        
        return true;
       }
       catch(Exception $e){
          return ['info' => $e->getMessage()];
       }
       

   }


   public function emailExist($email){
    
     return $this->userServiceRepository->email_exist($email);
    
   }

   public function  authenticate(Request $request){
        // can authenticate
        $user = $this->userServiceRepository->find_by_email($request->body->email);

        if(password_verify($request->body->password , $user->password_hash)){
          // delete redundant tokens
          if(!$user->allow_multiple_sessions) $this->_repository->deleteRedundantTokens($user->id);
         
          if($user->is_banned) return ["token" => null ,'authenticated' => false , 'user_is_banned' => true ];
         
          $info = $this->accountServiceRepository->user_account_details($user);
          
          $claims = ['user_id' => $user->id, 'email' => $user->email ,'is_admin' => true , 'account' => $info['account']->id ];
          
          $token  = $this->tokenService->generate($claims);   
          
          $this->userServiceRepository->save_token($token,$user);

          return ['token' => $token ,  'authenticated' => true];
        }else{
          return null;
        }

   }


   
   public function deleteAuthToken($email_from_claim){

     $user = $this->userServiceRepository->find_by_email($email_from_claim);

     if(!$user->allow_multiple_sessions) $this->userServiceRepository->deleteRedundantTokens($user->id);
   }


   function accountDetails(user $user){
      return $this->accountServiceRepository->user_account_details($user);
   }





}