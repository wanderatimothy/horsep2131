<?php

namespace core\aggregates;

use kernel\model;

class email_template extends model {
    
    public int $id = 0;

    public $account_id=0;

    public $template_purpose = '';

    public bool $is_active = false;

    public $subject = '';

    public $body_text = 'text';

    public $footer = 'text';

    public $timestamps_on = true;

    public static function foreign_keys()
    {
        return  ['account_id' => account::class];
    }

}