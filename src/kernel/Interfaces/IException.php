<?php

namespace kernel\Interfaces;

interface IException {

    public function errorMessage() :array ;

    public function infoMessage(): array;
} 