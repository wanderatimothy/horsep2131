<?php
namespace infrastructure\security;
use MiladRahimi\Jwt\Generator;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\Parser;
use MiladRahimi\Jwt\Exceptions\ValidationException;
use MiladRahimi\Jwt\Validator\DefaultValidator;

class Tokens {

    private $signer;

    private $generator;

    public $validator;

    private $parser;


    function __construct()
    {
        $this->signer = new HS256('3SsxDI5TWJStIw7KcFs6mRaxzQY2JqM7');

        $this->generator = new Generator($this->signer);

        $this->validator = new DefaultValidator();

        
    }

    function generateToken(array $claims){
        return $this->generator->generate($claims);
    }

    function claims($token){
        
        try{
            $parser = new Parser($this->signer);
            $data = $parser->parse($token);
            return $data;
        }catch(ValidationException $e){
            return false;
        }
    
    }

    function validate($token){
        $this->parser = new Parser($this->signer,$this->validator);
        try{
            $this->parser = new Parser($this->signer);
            $data = $this->parser->parse($token);
            return $data;
        }catch(ValidationException $e){
            return false;
        }
    
    }

}