<?php 
namespace infrastructure\database\specifications\Repositories;

use core\aggregates\token;
use core\aggregates\user;
use infrastructure\database\specifications\Repository;
class UserServiceRepository {
    
    public Repository $user_repo;

    private Repository $tokens_repo;

    public function __construct()
    {
        $this->user_repo = new Repository(user::class);

        $this->tokens_repo = new Repository(token::class);
    }


    public function email_exist(string $email):bool{

        $action = $this->user_repo->count(["email" => ":email" , "is_deleted" => 0],["email" => $email]);
        
        return $action == 1;
    }
 

    public function save_token(string $token_string , user $user ){

        $token = new token;
        $token->token_hash = password_hash($token_string , PASSWORD_DEFAULT);
        $token->user_id = $user->id;

        $this->tokens_repo->addToQue($token);
        $this->tokens_repo->saveChanges();
        
        return $this->tokens_repo->last_insert_id();

    }


    public function deleteRedundantTokens(user $user){
 
         $this->tokens_repo->destroy(array(
            "user_id" => $user->id
        ));

        return $this->tokens_repo->hasChanges();
    }

    
    public function find_by_email($email){

        $res = $this->user_repo->find(["email" => ":email" , "is_deleted " => 0 ],[],["email" => $email]);

        return (count($res) == 1)? $res[0] : null;
    }

    



}