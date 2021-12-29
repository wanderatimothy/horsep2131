<?php
namespace core\aggregates;

use kernel\model;

class subscription_type  extends model{

    public int $id = 0;

    public  string $type_name ='';

    public int $landlords_allowed = 0;

    public int $properties_allowed = 0;

    public int $units_allowed = 0;

    public int $accounts_allowed = 0;

    public int $managers_allowed = 0;

    public bool $manage_documents = false;

    public bool $timestamps_on = true;

}