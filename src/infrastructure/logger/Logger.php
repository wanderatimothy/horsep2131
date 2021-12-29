<?php

namespace infrastructure\logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonoLogger;


class Logger {

    private $logPath;

    public $logName;

    private  $_logger;
    
    public function __construct(string $path , $name)
    {
        $this->logPath = $path;
        $this->logName = $name;
    }

    private function stream(){
        $this->_logger = new MonoLogger($this->logName); 
        $this->_logger->pushHandler(new StreamHandler($this->logPath));
    }


    function log(){
        $this->stream();
        return $this->_logger;
    }

    
    
}