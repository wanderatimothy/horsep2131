<?php
namespace infrastructure\database\specifications\Repositories;

use core\aggregates\account;
use core\aggregates\landlord;
use core\aggregates\notification;
use core\aggregates\subscription_type;
use core\aggregates\wallet;
use infrastructure\database\specifications\Repository;

class AccountRepository extends Repository{


    function __construct()
    {
        parent::__construct(account::class);
    }

    function user_account_details($user_id){
        $sql = 'select * from accounts where user_id = :user_id ';
        $this->conn->runOperation($sql,['user_id' => $user_id]);
        $results = $this->conn->results(account::class);
        return count($results) > 0 ? $results[0] : null;
    }


    function subscriptionType(account $account):subscription_type{
        $sql = 'select * from subscription_types where id = :id ';
        $this->conn->runOperation($sql,['id' => $account->subscription_type_id]);
        $results = $this->conn->results(subscription_type::class);
        return $results[0];
    }

    function numberOfLandlords(account $account) :int{
        $sql = 'select count(id) as matches from landlords where user_id = :user_id';
        $this->conn->runOperation($sql,['user_id' => $account->user_id]);
        $results = $this->conn->results(landlord::class);
        if(empty($results)) return 0;
        return (int) $results[0]->matches;
    }

   function landlords(account $account){
        $sql = 'select * from landlords where user_id = :user_id';
        $this->conn->runOperation($sql,['user_id' => $account->user_id]);
        $results = $this->conn->results(landlord::class);
        return $results;
   }


  public function getWallet(account $account):wallet{
      $sql = "select * from wallets where account_id = :account_id";
      $this->conn->runOperation($sql,['account_id' => $account->id]);
      $results = $this->conn->results(wallet::class);
      return  !empty($results) ? $results[0] : null;
  }


  public function getAccountNotificationSettings ($account):notification {
    $sql = "select * from notifications where account_id = :account_id";
    $this->conn->runOperation($sql,['account_id' => $account->id]);
    $results = $this->conn->results(notification::class);
    return  !empty($results) ? $results[0] : null;
  }

}