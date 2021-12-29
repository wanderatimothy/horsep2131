<?php

namespace infrastructure\database\specifications\Repositories;

use core\aggregates\account;
use core\aggregates\managed_entity;
use core\aggregates\manager;
use core\aggregates\services\DateService;
use infrastructure\database\specifications\Repository;

class ManagerRepository extends Repository {

    public function  __construct()
    {
        parent::__construct(manager::class);
    }


    public function account($email ,account $account):manager{
        $sql = 'SELECT * FROM `managers` WHERE `email` = :email AND `account_id` => :account_id' ;
        $this->conn->runOperation($sql,["email" => $email , "account_id" => $account->id]);
        $res = $this->conn->results(manager::class);
        return empty($res)? null : $res[0];
    }

    public function contactAlreadyRegistered($contact , account $account){

        $sql = "SELECT COUNT (`id`) AS `matches` WHERE `contact` = :contact AND `account_id = :account`";
        $this->conn->runOperation($sql,["contact" => $contact , "account" => $account->id]);
        $res = $this->conn->results();
        return ($res[0]['matches'] > 0);
    }

    public function getManager($id,account $account) {
        $sql = 'SELECT * FROM `managers` WHERE `id` = :id AND `account_id` => :account_id' ;
        $this->conn->runOperation($sql,["id" => $id , "account" => $account->id]);
        $res = $this->conn->results();
        return empty($res) ? null : $res[0];
    }

    public function getAssignments($model , $role, $manager){

        $sql = "SELECT `id`  AS `role_id` , `model_id` AS `id` FROM `managed_entities` WHERE `managed_as` = :managed_as AND  `model` = :model AND `managers_id` = :manager ";
        $this->conn->runOperation($sql,["managed_as" => $role , "model" => $model , "manager" =>$manager->id]);
        return $this->conn->results();
    }

    public function assignment(string $model , int $id , manager $manager , $role){
        $sql = "SELECT * FROM `managed_entities` WHERE `managed_as` = :managed_as AND  `model` = :model AND `managers_id` = :manager  AND model_id = :id";
        $this->conn->runOperation($sql,["managed_as" => $role , "model" => $model , "manager" =>$manager->id  , "id" => $id]);
        $res = $this->conn->results(managed_entity::class);   
        return empty($res) ? null : $res[0];
    }

    function getManagedUnits($units ,  $deleted = 0){
        if(count($units) == 1){
            $sql = 'SELECT `units`.* , `floors`.`floor_name` FROM `units`  INNER JOIN `floors`  ON `units`.`floor_id`  =  `floors`.`id`  WHERE  `units`.`is_deleted` = :is_deleted  AND  `units`.`id` = :unit_id';
            $this->conn->runOperation($sql ,['unit_id' => $units[0] , "is_deleted"  => $deleted] );
        }else{
            $sql = 'SELECT `units`.* , `floors`.`floor_name` FROM `units`  INNER JOIN `floors`  ON `units`.`floor_id`  =  `floors`.`id`  WHERE  `units`.`is_deleted` = :is_deleted   AND  `units`.`id` IN (.'.implode(',',$units).'.) ';
            $this->conn->runOperation(["is_deleted"  => $deleted] );
        }
        $results = $this->conn->results(unit::class);
        return $results;
    }

    public function revokeAllAssignments(manager $manager){
        $sql = "UPDATE `managed_entities` SET `revoked` = :revoked  , `ending` = :ending  WHERE `manager_id` = :manager AND revoked = 0";
        $this->conn->runOperation($sql,["revoked" => 1 , "ending" => DateService::current_timestamp() , "manager" =>$manager->id ] );
        return $this->conn->affected_rows;
    }
  
}