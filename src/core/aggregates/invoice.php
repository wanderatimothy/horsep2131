<?php 

namespace core\aggregates;

use kernel\model;

class invoice extends model{

    public int $id = 0;

    public $reference_no = "";

    public bool $has_been_cleared = false;

    public bool $has_items = false;

    public $amount = 0.00;

    public $amount_cleared = 0.00;

    public $balance = 0.00;

    public bool $timestamps_on = true;

    public $model = "";

    public $model_id = 0;

    public $account_id = 0;
    
    public bool $softdeletes_on =true;
    
    public static function foreign_keys()
    {
        return ["account_id" => account::class];
    }


}