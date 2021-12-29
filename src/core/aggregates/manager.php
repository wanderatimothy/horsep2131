<?php 

namespace core\aggregates;

use kernel\model;

class manager extends model {

    public int $id = 0;

    public $name = '';

    public $email = '';

    public $contact = '';

    public $password = 'text';

    public $id_number = '';

    public $manager_photo = '';

    public bool $timestamps_on = true;

    public bool $softdeletes_on = true;

    public bool $is_suspended = true;

    public bool $account_activation = false;

    public $account_id = 0;

    public static function foreign_keys()
    {
        return  ['account_id' => account::class];
    }

}