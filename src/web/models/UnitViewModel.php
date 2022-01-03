<?php

namespace web\models;

use core\aggregates\account;
use core\aggregates\property;
use core\aggregates\unit;

class UnitViewModel {

    public $details;

    public $addon_types;

    public $property;

    public function __construct(unit $unit , property $property , $service)
    {
        $this->details = $unit;

        $this->property = $property;

        $this->addon_types = $service->addOnTypes();
    }
}