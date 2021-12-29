<?php
namespace core\aggregates;

use kernel\model;

class account  extends model{

    public int $id = 0;

    public  int $user_id = 0;

    public string $business_name = '';

    public string $business_email = '';

    public string $business_contact = '';

    public string $business_logo = '';

    public string $business_address = '';

    public string $website_url = '';

    public $tenants = 0;

    public $properties = 0;

    public $units = 0;

    public $landlords = 0;

    public string $dropbox_api_key = '';

    public int $subscription_type_id = 0;

    public bool $timestamps_on = true;


    public static function foreign_keys(){
        return [ 'user_id' => user::class , 'subscription_type_id' => subscription_type::class ];
    } 
}