<?php

namespace core\aggregates;

use kernel\model;

class add_on_type extends model {

    public int $id = 0;

    public $type_name = "";

    public $timestamps_on = true;
    
}