<?php

namespace infrastructure\database\specifications\Repositories;

use core\aggregates\tenant;
use infrastructure\database\specifications\Repository;
use stdClass;

class TenantRepository extends Repository{

    public function __construct()
    {
        parent::__construct(tenant::class);
    }

    public function tenantsOnProperty($property_id , $deleted = 0){
        if(is_array($property_id)){
            $sql = "SELECT `id` FROM `units` WHERE `is_deleted` = 0 AND `property_id`   IN ( " .implode(',',$property_id). ") ";
            $this->conn->runOperation($sql);
        }else{
            $sql = 'SELECT id FROM  `units`  WHERE `property_id` = :property_id  ';
            $this->conn->runOperation($sql,['property_id' => $property_id]);
        }
        $results = $this->conn->results();
        if(empty($results)) return [];
        $units = [];
        foreach($results as $res) $units[] = $res['id'];
        
        return $this->getTenantsFrom($units , $deleted);
    }


    
    public function getTenantsFrom(array $unit_ids , $deleted = 0){
        if(count($unit_ids) == 1){
            $sql = 'SELECT `tenants`.* , `units`.`label` FROM `tenants` INNER JOIN `units`  ON `tenants`.`unit_id`  =  `units`.`id`   WHERE tenants.`id` = :id';
            $this->conn->runOperation($sql,['id' => $unit_ids[0]]);
        }else{
            $sql = 'SELECT `tenants`.* , `units`.`label` FROM `tenants` INNER JOIN `units`  ON `tenants`.`unit_id`  =  `units`.`id`  WHERE   `tenants`.`unit_id` IN ( '. implode(',' , $unit_ids). ')  AND `tenants`.`is_deleted` =' .$deleted ;
            $this->conn->runOperation($sql);
        }
        return $this->conn->results(tenant::class);
    }



  public function getTenantsDueWithPeriods(array $unit_ids , $period_start , $period_end){

    if(count($unit_ids) > 1){

        $sql = "SELECT `id` , `names` , `email`, `contact`, `emergency_contact`  FROM `tenants` WHERE `is_deleted` = 0  `next_due_date` IS  BETWEEN (:period_start AND :period_end)   AND `unit_id` IN ( ". implode(',' , $unit_ids). ")  ";
   
        $this->conn->runOperation($sql,['period_start' => $period_start , "period_end" =>$period_end]);

    }else{
        $sql = "SELECT `id` , `names` , `email`, `contact`, `emergency_contact`  FROM `tenants` WHERE `is_deleted` = 0  `next_due_date` IS  BETWEEN (:period_start AND :period_end)   AND `unit_id` = :unit  ";

        $this->conn->runOperation($sql,["unit" => $unit_ids[0] , 'period_start' => $period_start , "period_end" =>$period_end]);

    }
    return $this->conn->results(stdClass::class);

  }


  public function getTenantsDueToday(array $unit_ids){

    if(count($unit_ids) > 1){

        $sql = "SELECT `id` , `names` , `email`, `contact`, `emergency_contact`  FROM `tenants` WHERE `is_deleted` = 0  `next_due_date` = CURRENT_DATE()   AND `unit_id` IN ( ". implode(',' , $unit_ids). ")  ";
   
        $this->conn->runOperation($sql);

    }else{
        $sql = "SELECT `id` , `names` , `email`, `contact`, `emergency_contact`  FROM `tenants` WHERE `is_deleted` = 0  `next_due_date` = CURRENT_DATE()   AND `unit_id` = :unit  ";

        $this->conn->runOperation($sql,["unit" => $unit_ids[0] ]);

    }
    return $this->conn->results(stdClass::class);
  }


  
}