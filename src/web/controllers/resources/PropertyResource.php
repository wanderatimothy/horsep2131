<?php

namespace web\controllers\resources;

use core\aggregates\services\PropertyService;
use core\aggregates\services\ValidationService;
use kernel\Interfaces\IResource;

class PropertyResource extends _BaseResource implements IResource{
    private $propertyService;

    public function __construct()
    {
        parent::__construct();

        $this->propertyService = new PropertyService;

    }




    public function index()
    {
        $data = $this->propertyService->properties($this->account);

        return respondWithJson($data , ['content-type:application/json'] ,200);

    }

    public function property_units(int $id){

        $property = $this->propertyService->repository->getById($id , $this->account);

        if(is_null($property)) return respondWithJson(['errors' => ['info' => 'Property was not found'] , 'success' => false ] , ['content-type: application/json'] , 404);

        $data = $this->propertyService->getPropertyUnits($property->id);

        return respondWithJson($data , ['content-type:application/json'] ,200);

    }

    public function create()
    {
        $validations = ValidationService::PropertyForm($this->request);

        if(count($validations) == 0){

            $created = $this->propertyService->create_property($this->request,$this->account);

            if(is_array($created))  return respondWithJson(['errors' => $created , 'success' => false ] , ['content-type: application/json'] , 422);

            if($created == true)    return respondWithJson(['errors' => [] , 'success' => true ] , ['content-type: application/json'] , 201);

            if($created == false)    return respondWithJson(['errors' => ['info' => ' bad request '] , 'success' => false ] , ['content-type: application/json'] , 500);

        }

        return respondWithJson(['errors' => $validations , 'success' => false ] , ['content-type: application/json'] , 422);


    }

    public function create_unit(int $id){

        $validations = ValidationService::UnitForm($this->request);
        if(count($validations) > 0 ) return respondWithJson(['errors' => $validations , 'success' => false ] , ['content-type: application/json'] , 422);

        $property = $this->propertyService->repository->getById($id , $this->account);

        if(is_null($property)) return respondWithJson(['errors' => ['info' => 'Property was not found'] , 'success' => false ] , ['content-type: application/json'] , 404);

        $created = $this->propertyService->create_property_units($this->request,$this->account,$property);

        if(is_array($created))  return respondWithJson(['errors' => $created , 'success' => false ] , ['content-type: application/json'] , 422);
        
        if($created == true)    return respondWithJson(['errors' => [] , 'success' => true ] , ['content-type: application/json'] , 201);

        if($created == false)    return respondWithJson(['errors' => ['info' => ' bad request '] , 'success' => false ] , ['content-type: application/json'] , 500);



    }

    public function edit($id)
    {
        
    }

    public function delete( $id)
    {
        $property = $this->propertyService->repository->getById($id , $this->account);

        if(is_null($property))  return respondWithJson(['errors' => ['error' => 'Unknown property reference'] , 'success' => false ] , ['content-type: application/json'] , 404);

        $this->propertyService->deleteProperty($property , $this->account);

        return respondWithJson(["errors" => [] , "success" => true] , ['content-type:application/json'] ,201);


    }

    public function property_types(){

        $data = $this->propertyService->propertyTypes();
        return respondWithJson($data,['content-type: application/json'],200);
    }

    public function floors(){
        $data = $this->propertyService->floors();
        return respondWithJson($data,['content-type: application/json'],200);
    }
 }