<?php

namespace core\aggregates;

use kernel\model;

class tenant extends model {

    public int $id = 0;

    public  $unit_id = 0;

    public $names = '';

    public $email = '';

    public $contact = '';

    public $gender = '';

    public $number_of_people = 0;

    public $number_of_pets = 0;

    public $emergency_person_names = '';

    public $emergency_person_contact = '';

    public $tenant_photo = '';

    public $renting_purpose ='';

    public $next_due_date = 'datetime';

    public $date_of_entry = 'datetime';

    public $date_of_departure = 'datetime';

    public $remarks = '';

    public bool $has_exemption = false;

    public bool $softdeletes_on = true;

    public bool $timestamps_on = true;


    public static function foreign_keys()
    {
        return  ['unit_id' => unit::class];
    }

}