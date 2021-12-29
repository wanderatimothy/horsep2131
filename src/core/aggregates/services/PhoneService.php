<?php

namespace core\aggregates\services;


class PhoneService {

    public const VALID_PHONE_LENGTH = [10,12];

    public static function phoneNumberIsValid($phone_number){

    $clean = str_replace(["(" , ")" , "," ,"+" , " "],"",trim($phone_number));

    $length = strlen($clean);

    if(!in_array($length,self::VALID_PHONE_LENGTH)) return false;

    if(!ctype_digit($clean)) return false;

    if($length == self::VALID_PHONE_LENGTH[1]):
    
      return (!in_array(substr($clean,0,2),array_keys(self::area_codes()))) ? false :  "+".$clean;
    
    else:

       return(str_starts_with("0",$clean))? "+256".ltrim($clean,"0") : false;

    endif;

    return false;

    }



    public static function area_codes():array{
        return [
            "256" => "UGANDA",
            "254" => "KENYA",
            "255" => "TANZANIA",
            "250" => "RWANDA",
            "257" => "BURUNDI",
            "211" => "S SUDAN",
            "252" => "SOMALIA"
        ];
    }

}