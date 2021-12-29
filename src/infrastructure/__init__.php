<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;


$log = new Logger('new test log');
$log->pushHandler(new StreamHandler('logs/log_file.log', Logger::WARNING));

die;