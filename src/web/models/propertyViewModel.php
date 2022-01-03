<?php

namespace web\models;

use core\aggregates\account;
use core\aggregates\property;
use core\aggregates\services\PropertyService;
use core\aggregates\services\SettingsService;

class propertyViewModel {


    public $details;

    public $customFields;

    public $addonTypes;

    public $onboardingSettings;

    public $fieldTypes;


    public function __construct(property $property , account $account)
    {
        $service = new PropertyService();

        $this->details = $property;

        $this->addonTypes = $service->addOnTypes();

        $service = new SettingsService();

        $this->onboardingSettings = $service->OnboardingSettings($account);

        $this->fieldTypes = $service->fields();

        $this->customFields = $service->settingsRepository->getCustomFields($property::class ,  $property->id);

    }





}