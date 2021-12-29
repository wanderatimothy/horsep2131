<?php
namespace core\aggregates;

use kernel\model;

class collection extends model {

    public int $id = 0;

    public $starting_from = "datetime";

    public $ending_on = "datetime";

    public $model_id = 0;

    public $model = "";

    public $report_type = "";

    public $total_collected = 0.00;

    public bool $softdeletes_on = true;

    public bool $timestamps_on = true;

    public $account_id = 0;

    public static function foreign_keys()
    {
        return ["account_id" => account::class];
    }
}