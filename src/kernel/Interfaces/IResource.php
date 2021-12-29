<?php

namespace kernel\Interfaces;


interface IResource {

    public function index();

    public function edit($id);

    public function create();

    public function delete( $id);
}