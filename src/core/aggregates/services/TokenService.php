<?php

namespace core\aggregates\services;

use infrastructure\security\Tokens;
use web\libs\Request;

class TokenService {


     public $tokenManager;

    function __construct()
    {     
        $this->tokenManager = new Tokens();
    }

    function generate(array $claims) {
        return $this->tokenManager->generateToken($claims);
    }

    function validate($token){
        return $this->tokenManager->validate($token);
    }

    public  function validateToken(Request $request){

        $token = $request->get_http_header('Authentication');
         return $this->validate($token);
    }



}

