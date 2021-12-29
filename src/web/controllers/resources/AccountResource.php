<?php
namespace web\controllers\resources;

use core\aggregates\services\AccountService;
use core\aggregates\services\ValidationService;
use kernel\Interfaces\IResource;

class AccountResource extends _BaseResource implements IResource{

    private AccountService $accountService;

    public function __construct()
    {
        parent::__construct();

        $this->accountService = new AccountService();
    }

    public function index()
    {
        $data = $this->account;
        $data->user_id = 0;
        return respondWithJson($data , ['content-type:application/json'] ,200);

    }

    public function edit($id)
    {

      if( is_int($id) && $this->account->id  != $id) return respondWithJson(['errors' => ['error' => 'Un acceptable operation Account will be locked due malicious activity'] , 'success' => false ] , ['content-type: application/json'] , 500);
    
       $validations = ValidationService::AccountForm($this->request);

       if(count($validations)  == 0) {
        $update = $this->accountService->updateAccountDetails($this->request,$this->account);

        if($update == true)    return respondWithJson(['errors' => [] , 'success' => true ] , ['content-type: application/json'] , 201);
        
        if(is_array($update))  return respondWithJson(['errors' => $update , 'success' => false ] , ['content-type: application/json'] , 422);
       }
       return respondWithJson(['errors' => $validations , 'success' => false ] , ['content-type: application/json'] , 422);

      
    }

    public function delete($id)
    {
        
    }

    public function create()
    {
        
    }
}