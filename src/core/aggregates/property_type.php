<?php

namespace core\aggregates;

use kernel\model;

class property_type extends model {

    public int $id = 0;

    public string $name = '';

    public string  $description = 'text';

    public bool $has_units = false;

    public bool $has_shareable_units = false;

    public bool $is_sharable = false;

    public bool $softdeletes_on = true;

    public bool $timestamps_on = true;
    
}