<?php

use core\aggregates\account;
use core\aggregates\action_trail;
use core\aggregates\add_on_type;
use core\aggregates\advance;
use core\aggregates\advance_log;
use core\aggregates\cf_value;
use core\aggregates\collection;
use core\aggregates\custom_field;
use core\aggregates\disbursement;
use core\aggregates\document;
use core\aggregates\email_template;
use core\aggregates\field;
use core\aggregates\floor;
use core\aggregates\invoice;
use core\aggregates\landlord;
use core\aggregates\managed_entity;
use core\aggregates\manager;
use core\aggregates\notification;
use core\aggregates\on_boarding_rule;
use core\aggregates\payment;
use core\aggregates\property;
use core\aggregates\property_type;
use core\aggregates\renting_mode;
use core\aggregates\renting_mode_setting;
use core\aggregates\sent_notification;
use core\aggregates\state_machine;
use core\aggregates\sub_wallet;
use core\aggregates\subscription_type;
use core\aggregates\suspension;
use core\aggregates\tenant;
use core\aggregates\token;
use core\aggregates\transaction;
use core\aggregates\unit;
use core\aggregates\unit_addon;
use core\aggregates\user;
use core\aggregates\wallet;
use infrastructure\database\DB\table;
use infrastructure\database\migrations\migrator;

migrator::run(function(){
    global $models;
     $models = [

        new user,
        new subscription_type,
        new account,
        new landlord,
        new token,
        new wallet,
        new property_type,
        new property,
        new floor,
        new unit,
        new add_on_type,
        new unit_addon,
        new document,
        new state_machine,
        new tenant,
        new manager , 
        new managed_entity ,
        new on_boarding_rule,
        new renting_mode,
        new renting_mode_setting,
        new field , 
        new custom_field,
        new cf_value,
        new notification,
        new sent_notification,
        new suspension,
        new email_template,
        new transaction,
        new payment,
        new collection,
        new invoice,
        new disbursement,
        new advance,
        new advance_log,
        new action_trail,
        new sub_wallet,
    ];

    global $tables;
    $tables = [];
    foreach($models as $model){
        $tables[] = new table($model);
    }

    unset($models);

    migrator::reverse(function(){
        global $tables;
        return array_reverse($tables);
    });

    return $tables;

});