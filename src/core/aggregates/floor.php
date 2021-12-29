<?php

namespace core\aggregates;

use kernel\model;

class floor extends model {

    public int $id = 0;

    public $floor_name = '';

    public bool $softdeletes_on = true;

    public bool $timestamps_on = true;

}