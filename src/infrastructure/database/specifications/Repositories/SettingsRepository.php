<?php

namespace  infrastructure\database\specifications\Repositories;

use core\aggregates\account;
use core\aggregates\custom_field;
use core\aggregates\field;
use core\aggregates\on_boarding_rule;
use core\aggregates\renting_mode;
use core\aggregates\renting_mode_setting;
use infrastructure\database\specifications\Repository;
use kernel\model;

class SettingsRepository extends Repository  {

    public function __construct()
    {
        parent::__construct(renting_mode::class);
    } 

    public function getFieldType($id){
        $sql = 'select * from fields where id = :id';
        $this->conn->runOperation($sql,['id' => $id]);
        return $this->conn->results(field::class)[0];

    }

    public function renting_modes(account $account , int $deleted = 0){
        $sql = 'select * from renting_modes where account_id = :account_id and is_deleted = :deleted';
        $this->conn->runOperation($sql,['account_id' => $account->id ,"deleted" => $deleted]);
        return $this->conn->results(renting_mode::class);
    }

    public function rentingModeById($id){
        $sql = 'select * from renting_modes where id = :id ';
        $this->conn->runOperation($sql,['id' => $id ]);
        $res = $this->conn->results(renting_mode::class);
        return (empty($res))? null : $res[0];
    }

    public function rentingModeSettingById($id){
        $sql = 'select * from renting_mode_settings where id = :id ';
        $this->conn->runOperation($sql,['id' => $id ]);
        $res = $this->conn->results(renting_mode_setting::class);
        return (empty($res))? null : $res[0];
    }

    public function getRentingModeSetting($id , string $model){
        $sql = 'select rms.* , rm.cycle , rm.grace_period ,rm.pay_condition  from renting_mode_settings rms inner join renting_modes rm on rms.mode_id = rm.id  where rms.id = :id and rms.model = :model ';
        $this->conn->runOperation($sql,['id' => $id  , "model" => $model]);
        $res = $this->conn->results(renting_mode_setting::class);
        return (empty($res))? null : $res[0];
    }

    

    public function getOnBoardingSettings(account $account):on_boarding_rule{
        $sql = 'select * from on_boarding_rules where account_id = :account_id';
        $this->conn->runOperation($sql,['account_id' => $account->id]);
        return $this->conn->results(on_boarding_rule::class)[0];
    }

    public function getCustomFields($model , account $account , $deleted = 0){
        $sql = "select cf.* , f.type,f.minimum,f.maximum,f.maxLength from custom_fields cf inner join fields f on cf.field_id = f.id  where cf.account_id = :account_id and cf.model = :model  ";
        $this->conn->runOperation($sql,['account_id' => $account->id ,"model" => $model ]);
        return $this->conn->results(custom_field::class);

    }

}