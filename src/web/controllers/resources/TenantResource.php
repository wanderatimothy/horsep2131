<?php

namespace web\controllers\resources;

use core\aggregates\services\PropertyService;
use core\aggregates\services\TenantService;
use core\aggregates\services\ValidationService;
use kernel\Interfaces\IResource;

class TenantResource extends _BaseResource implements IResource{

    private TenantService $tenantService;

    private PropertyService $propertyService;

    public function __construct()
    {
        parent::__construct(); 
        $this->tenantService = new TenantService();
        $this->propertyService = new PropertyService();
        
    }

    public function index()
    {
        $data = $this->tenantService->getTenants($this->account , false);
        return respondWithJson($data , ['content-type:application/json'] ,200);
   
    }

    public function create()
    {
        $validations = ValidationService::TenantForm($this->request);

        if(count($validations) == 0){

            $created = $this->tenantService->createTenant($this->request,$this->account);

            if(is_array($created))  return respondWithJson(['errors' => $created , 'success' => false ] , ['content-type: application/json'] , 422);

            if($created == true)    return respondWithJson(['errors' => [] , 'success' => true ] , ['content-type: application/json'] , 201);

            if($created == false)    return respondWithJson(['errors' => ['info' => ' bad request '] , 'success' => false ] , ['content-type: application/json'] , 500);
        }

        return respondWithJson(['errors' => $validations , 'success' => false ] , ['content-type: application/json'] , 422);

    }

    public function edit($id)
    {
 

        
    }

    public function delete( $id)
    {
        
        

    }

    public function TenantsOnProperty($id){
        $property = $this->propertyService->repository->getById($id, $this->account);
        $data = $this->tenantService->repository->tenantsOnProperty($property->id,0);
        return respondWithJson($data , ['content-type:application/json'] ,200);
    }
}