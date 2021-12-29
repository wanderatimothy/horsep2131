<?php
namespace core\aggregates;

use kernel\model;

class landlord  extends model{

    public int $id = 0;

    public  string $names = '';

    public string $email = '';

    public $password = "text";

    public string $contact = '';

    public  $last_payout = 'datetime';

    public  $last_payout_amount = 0.00;

    public  $current_collection_amount = 0.00;

    public  $amount_expected = 0.00;

    public  $tenants = 0;

    public  $managers_commission = 0.00;

    public bool $timestamps_on = true;

    public bool $softdeletes_on =  true;

    public int $user_id = 0;

    public static function foreign_keys()
    {
        return [ 'user_id' => user::class  ];
    }
}