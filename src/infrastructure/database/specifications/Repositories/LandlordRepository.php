<?php
namespace infrastructure\database\specifications\Repositories;

use core\aggregates\account;
use core\aggregates\landlord;
use core\aggregates\property;
use infrastructure\database\specifications\Repository;

class LandlordRepository extends Repository{


    function __construct()
    {
        parent::__construct(landlord::class);
    }



    // eger loading example
    function properties($landlords_ids , $deleted = 0 ){
     if(is_int($landlords_ids)){
         $sql = "SELECT * FROM `properties` WHERE `is_deleted` = :is_deleted AND `landlord_id` = :id";
         $this->conn->runOperation($sql , ['id' => $landlords_ids ,'is_deleted' => $deleted ]);
     }else{
        $sql = 'SELECT * FROM `properties` WHERE `is_deleted` = :is_deleted  AND `landlord_id` IN('.$landlords_ids.')';
        $this->conn->runOperation($sql , ["is_deleted" => $deleted]);
     }
     $results = $this->conn->results(property::class);
     return $results;

    }

    function numberOfProperties(string $landlords_ids) :int{
        $sql = 'SELECT COUNT(`id`) AS `matches` FROM `properties` WHERE `is_deleted` = 0  AND `landlord_id` IN('.$landlords_ids.')';
        $this->conn->runOperation($sql);
        $results = $this->conn->results(property::class);
        return empty($results) ? 0 : $results[0]->matches;
    }


    public function getLandlord($id , account $account ,$deleted = 0 ):landlord{
        $sql = 'SELECT  *   FROM `landlords` WHERE `is_deleted` = 0  AND `landlord_id` = :landlord AND `user_id` = :user ';
        $this->conn->runOperation($sql,['is_deleted' => $deleted , "landlord" => $id , "user" =>$account->user_id]);
        $results = $this->conn->results(landlord::class);
        return empty($results) ? null : $results[0];
    }

    
    

    
}