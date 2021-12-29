<?php 
namespace infrastructure\database\specifications\Repositories;

use core\aggregates\user;
use infrastructure\database\specifications\Repository;

class UserRepository  extends Repository{
    

    public function __construct(){

        parent::__construct(user::class);
      
    }

    function emailExists($email):bool{
        $sql = 'select count(`id`) as matches from '.$this->table .' where email = :email';
        $this->conn->runOperation($sql,['email' => $email]);
        $matches = $this->conn->results();
        return ($matches[0]['matches']  == 1); 
    }

    function getUserByEmail($email){
        $sql = 'select * from users where email = :email ';
        $this->conn->runOperation($sql,['email' => $email]);
        return $this->conn->results(user::class)[0];
    }

    public function deleteRedundantTokens($user_id){

        $sql = 'delete from tokens where user_id = :user_id';
        $this->conn->runOperation($sql,['user_id' => $user_id]);
        return $this->conn->affected_rows;  

    }

    

}