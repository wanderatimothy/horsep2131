<?php
namespace core\aggregates;

use kernel\model;

class field extends model {

    public int $id = 0;

    public  $type = '';

    public  $maxLength = 200;

    public $minimum = 0;

    public $maximum = 0;
    // should be provided
    public  $required = false;

}