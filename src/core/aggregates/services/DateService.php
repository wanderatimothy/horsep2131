<?php

namespace  core\aggregates\services;

use DateTime;

class DateService {


    public static function current_timestamp(){

        $date = new DateTime();
        
        return $date->format("Y-m-d:H:i:s");

    }


    public static function today(){

        return date("D",strtotime("Today"));
    }


    public static function period(string $string){
        return date("Y-m-d:H:i:s",strtotime($string));
    }

    
}

