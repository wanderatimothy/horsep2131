<?php
namespace infrastructure\database\specifications\Repositories;

use core\aggregates\notification;
use core\aggregates\sent_notification;
use infrastructure\database\specifications\Repository;

class NotificationRepository extends Repository {

    public function __construct()
    {
        parent::__construct(notification::class);
    }

    public function UnseenNotifications(string $model, int $id){

        $sql = "SELECT *  FROM `sent_notifications`   WHERE   `recipient_account_model` = :model  AND `recipient_account_id` =:id AND `seen` = 0 ";
        
        $this->conn->runOperation($sql,["model" => $model , "id" => $id]);

        return $this->conn->results(sent_notification::class);   
        
    }


    public function SeenNotifications(string $model, int $id){

        $sql = "SELECT *  FROM `sent_notifications`   WHERE   `recipient_account_model` = :model  AND `recipient_account_id` =:id AND `seen` = 1 ";
        
        $this->conn->runOperation($sql,["model" => $model , "id" => $id]);

        return $this->conn->results(sent_notification::class);   
        
    }




    public function findNotification($id){

        $sql = "SELECT *  FROM `sent_notifications` WHERE  `id` =:id ";
        
        $this->conn->runOperation($sql,[ "id" => $id]);

        $res = $this->conn->results(sent_notification::class);

        return empty($res)? null : $res[0];
    }


    




}