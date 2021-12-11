<?php
namespace web\controllers;

use web\libs\Request;

abstract class _BaseController {
   
    protected $request;

    public function __construct()
    {
      $this->request = new Request();
    }
   


}