<?php
namespace core\aggregates;

use kernel\model;

class managed_entity extends model{

    public int $id = 0;

    public $model = '';

    public $model_id =0;

    public $managed_as = '';

    public $managers_id = 0;

    public $starting_from = 'datetime';

    public $ending = "datetime";

    public $revoked = false;

    public bool $timestamps_on = true;

    public bool $softdeletes = true;

    public static function foreign_keys()
    {
        return  ['managers_id' => manager::class];
    }

}