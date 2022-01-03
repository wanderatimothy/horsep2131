<?php

namespace web\models;

use core\aggregates\services\SettingsService;

class propertiesPageViewModel {

    public $fieldTypes;

    public function __construct()
    {
        $service = new SettingsService;

        $this->fieldTypes = $service->fields();
    }
}