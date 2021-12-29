<?php
namespace web\controllers\resources;

use core\aggregates\services\PropertyService;
use core\aggregates\services\ValidationService;
use kernel\Interfaces\IResource;

class LandlordResource extends _BaseResource implements IResource {

    private PropertyService $propertyService;

    public function __construct()
    {
        parent::__construct();

        $this->propertyService = new PropertyService;

    }


    public function index()
    {
        $data = $this->propertyService->getLandlords($this->account);
        return respondWithJson($data , ['content-type:application/json'] ,200);
    }


    public function edit($id)
    {
        
    }

    public function create()
    {
        $validations = ValidationService::LandlordForm($this->request);

        if(count($validations) == 0){

            $created = $this->propertyService->create_landlord($this->request,$this->account);

            if(is_array($created))  return respondWithJson(['errors' => $created , 'success' => false ] , ['content-type: application/json'] , 422);

            if($created == true)    return respondWithJson(['errors' => [] , 'success' => true ] , ['content-type: application/json'] , 201);

            if($created == false)    return respondWithJson(['errors' => ['info' => ' bad request '] , 'success' => false ] , ['content-type: application/json'] , 500);

        }

        return respondWithJson(['errors' => $validations , 'success' => false ] , ['content-type: application/json'] , 422);


    }

    public function delete($id)
    {
        
    }
}
