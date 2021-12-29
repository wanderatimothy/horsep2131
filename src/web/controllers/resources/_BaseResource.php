<?php

namespace web\controllers\resources;

use core\aggregates\services\AuthService;
use core\aggregates\services\TokenService;
use web\libs\Request;

class _BaseResource {


    private $tokenData;

    protected $request;

    protected $account;

    public function __construct()
    {
        $this->request = new Request();

        
        $tokenService = new TokenService();
        $this->tokenData = $tokenService->validateToken($this->request);
        if($this->tokenData == false){
            // unauthenticated request
            return respondWithJson([ 'error' => 'unauthenticated request login and try again'],null,401);
            die;
            
        }else{
            $auth = new AuthService();
            $this->account = $auth->accountDetails($this->tokenData['user_id']);
        }

        
    }

    public function getClaims(){
        return $this->tokenData;
    }
}
