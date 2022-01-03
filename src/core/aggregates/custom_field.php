<?php

namespace core\aggregates;

use kernel\model;

class custom_field extends model {

    public int $id = 0;

    public $model = '';

    public $model_id = 0;

    public $name = "";

    public $type = "";

    public $value = "";

    public bool $timestamps_on =true;

    public bool $softdeletes_on = true;



}