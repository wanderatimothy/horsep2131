<?php
namespace infrastructure\database\specifications\Repositories;

use core\aggregates\account;
use core\aggregates\property;
use core\aggregates\property_type;
use core\aggregates\unit;
use infrastructure\database\specifications\Repository;

class PropertyRepository extends Repository{


    function __construct()
    {
        parent::__construct(property::class);
    }


    function getById($id , account $account){
        $sql = 'select properties.* , landlords.names  from  properties  inner join landlords  on properties.landlord_id = landlords.id where properties.id = :id  and landlords.user_id = :user_id ';
        $this->conn->runOperation($sql,['id' => $id , "user_id" => $account->user_id]);
        $results = $this->conn->results(property::class);
        return count($results) > 0 ? $results[0] : null;
    }

    

    function countUnits($ids) : int {
        if(count($ids) == 1 ){
            $sql = 'SELECT COUNT(`id`) AS `matches` FROM `units` WHERE `property_id` = :property_id ';
            $this->conn->runOperation($sql , ['property_id'=> $ids[0]]);
        }else{
            $sql = 'SELECT COUNT(`id`) AS `matches` FROM `units`  WHERE  `property_id` IN (.'.implode(',',$ids).'.) ';
            $this->conn->runOperation($sql);
        }
        $results = $this->conn->results();
        return count($results) > 0 ? $results[0]['matches'] : 0;
    }

    function getUnits($properties , bool $deleted = false){
        if(count($properties) == 1){
            $sql = 'SELECT `units`.* , `floors`.`floor_name` FROM `units`  INNER JOIN `floors`  ON `units`.`floor_id`  =  `floors`.`id`  WHERE  `units`.`is_deleted` = :is_deleted  AND  `units`.`property_id` = :property_id';
            $this->conn->runOperation($sql ,['property_id' => $properties[0] , "is_deleted"  => $deleted] );
        }else{
            $sql = 'SELECT `units`.* , `floors`.`floor_name` FROM `units`  INNER JOIN `floors`  ON `units`.`floor_id`  =  `floors`.`id`  WHERE  `units`.`is_deleted` = '.$deleted.'  AND  `units`.`property_id` IN (.'.implode(',',$properties).'.) ';
        }
        $results = $this->conn->results(unit::class);
        return $results;
    }


   function getUnitsWithTenants($property_ids){
    
    if(is_array($property_ids)){
        $sql = "SELECT `id`  FROM `units` WHERE `is_deleted` = 0 AND   `number_of_occupants` > 0  AND `property_id` IN (".implode(',',$property_ids).") ";
       
        $this->conn->runOperation($sql);

    }else{
        $sql = "SELECT `id`  FROM `units` WHERE `is_deleted` = 0 AND   `number_of_occupants` > 0  AND `property_id` = :property ";
        
        $this->conn->runOperation($sql , ['property'=>$property_ids]);
    }
    $results = $this->conn->results();
    
    return $results;

   }
  
    
    public function getUnit(int $id):unit{
    
        $sql = "SELECT * FROM `units` WHERE `id` = :id";
        $this->conn->runOperation($sql,['id' => $id]);
        $results = $this->conn->results(unit::class);
        return  !empty($results)? $results[0] : null; 
    }


    public function getPropertyType($id):property_type{
        $sql = 'select * from property_types where id = :id';
        $this->conn->runOperation($sql,['id' => $id]);
        $results = $this->conn->results(property_type::class);
        return  !empty($results)? $results[0] : null; 
    }
}