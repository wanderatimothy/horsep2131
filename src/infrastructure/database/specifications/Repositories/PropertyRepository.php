<?php
namespace infrastructure\database\specifications\Repositories;

use core\aggregates\account;
use core\aggregates\property;
use core\aggregates\property_block;
use core\aggregates\property_type;
use core\aggregates\unit;
use core\resources\property_resource;
use infrastructure\database\specifications\Repository;

class PropertyServiceRepository {

    public Repository $properties_repo;

    public Repository $units_repo;

    public Repository $types_repo;

    public Repository $blocks_repo;


    public function __construct()
    {
        $this->properties_repo = new Repository(property::class);

        $this->units_repo = new Repository(unit::class);

        $this->types_repo = new Repository(property_type::class);
        
        $this->blocks_repo = new Repository(property_block::class);

    
    }

    function getById($id , account $account){
        
        $resource = $this->properties_repo->findByIdAsResource(property_resource::class , $id);

        if($resource['landlord']['user_id'] != $account->user_id) return [];

        return $resource;
    }

    

    function count_units_in_properties(array $property_ids , $deleted = 0) : int {

        $blocks = $this->get_property_blocks($property_ids);

        if(count($blocks) == 0) return 0;

        $block_ids = array();

        foreach($blocks as $block) $block_ids[] = $block->id;
        

        if(count($block_ids) == 1){

            return $this->units_repo->count(["block_id" => $block_ids[0] , "is_deleted" => $deleted ]);

        }else{

            return $this->units_repo->count(['IN' => array(
                'filter' => 'block_id',
                'range' => $block_ids
            ),
            'is_deleted' => $deleted    
        ]);
        }

    }

    public function get_property_blocks(array $property_ids , $deleted = 0 ){

        if(count($property_ids) == 1) {

            return $this->blocks_repo->find(['property_id' => $property_ids[0] , 'is_deleted' => $deleted]);
        }

        return $this->blocks_repo->find([
            'IN' => array(
                'filter' => 'property_id',
                'range' => $property_ids
            ),
            'is_deleted' => $deleted
        ]);
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
    
        $sql = "SELECT `units`.* , `floors`.`floor_name` FROM `units`  INNER JOIN `floors`  ON `units`.`floor_id`  =  `floors`.`id` WHERE `units`.`id` = :id";
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