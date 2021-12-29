<?php

namespace core\contracts;

interface IConnection {

    public function connect();

    public function runOperation($sql , array $parameters = null);

    public function results(string $class = null);

}