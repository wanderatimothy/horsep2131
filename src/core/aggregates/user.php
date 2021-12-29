<?php
namespace core\aggregates;

use kernel\model;

class user  extends model{

    public int $id = 0;

    public  string $email = '';

    public string $password_hash = 'text';

    public string $contact = '';

    public string $security_hash = 'text';

    public $is_banned = false;

    public bool $is_active = false;

    public $allow_multiple_sessions = false;

    public bool $timestamps_on = true;



}