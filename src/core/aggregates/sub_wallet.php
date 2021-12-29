<?php

namespace core\aggregates;

use kernel\model;

class sub_wallet extends model {

    public int $id = 0;

    public $main_wallet = 0;

    public $account_model = "";

    public $account_model_id = 0;

    public $cash_at_hand = 0.00;

    public $cash_in_wallet = 0.00;

    public $security_cash_at_hand = 0.00;

    public $security_cash_in_wallet = 0.00;

    public bool $softdeletes_on = true;

    public bool $timestamps_on = true;

}