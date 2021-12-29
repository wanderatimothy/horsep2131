<?php 

namespace core\aggregates;

use kernel\model;

class sent_notification extends model {

    public int $id = 0;

    public $seen  = false;

    public $message = 'text';

    public $recipient_account_model = '';

    public $recipient_account_id = 0;

    public $timestamps_on = true;

    public $softdeletes_on =true;

    
}