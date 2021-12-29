<?php

namespace infrastructure\database\seeders;

use core\aggregates\state_machine;
use infrastructure\database\specifications\Repository;
use kernel\model;

final class seeder  {

    public $data = [];

    private $repository;

    public function __construct(callable $function)
    {
     
        $this->data = call_user_func($function);
        $this->repository = new Repository(state_machine::class);
        
    }


    public function run(){
        
        $this->repository->data = $this->data;
        $this->repository->saveChanges();
        echo "<br/> <p> Database data seeded verify the data</p>";
    }

    public function reverse(){
        // Truncate statement
    }
}