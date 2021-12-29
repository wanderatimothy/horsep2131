<?php
namespace web\libs;

class Session {


    public static function error($message){
        $_SESSION['error'] = $message;
    }

    public static function _error(string $key){
        return isset($_SESSION['error'][$key])? $_SESSION['error'][$key] : null;
    }


   public static function info($message){
            $_SESSION['info'] = $message;
    }

    public static function _info(string $key){
        return isset($_SESSION['info'][$key])? $_SESSION['info'][$key] : null;
    }

  public static function set(string $key , $value){
        $_SESSION[$key] = $value;
  }

  public static function get(string $key){
      return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
  }

  public static function _clear(string $key){
      unset($_SESSION[$key]);
  }

  public static function initialize(){
        // start the application session
        session_start();
        // session vars
        if(isset($_SESSION['current_location_url'])){
            $_SESSION['prev_location_url'] = $_SESSION['current_location_url'];
            $_SESSION['current_location_url'] = $_SERVER['REQUEST_URI'];
        }else{
            $_SESSION['current_location_url'] = $_SERVER['REQUEST_URI'];
        }
  }

  public static function clearTempData(){
    $_SESSION['error'] = [];
    $_SESSION['info'] = [];
  }

}