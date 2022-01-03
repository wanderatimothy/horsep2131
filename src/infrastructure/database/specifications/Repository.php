<?php

namespace infrastructure\database\specifications;

use core\contracts\IRepository;
use core\pluralize;
use infrastructure\database\DB\connection;
use kernel\model;
use stdClass;

class Repository implements IRepository
{

    protected $table;

    private $processing_que = [];

    protected connection $conn;

    private $model;

    private $class;

    public function __construct(string $class)
    {

        $this->conn = new connection();

        $this->model = new $class;

        $this->class = $class;
    }

    function addToQue(model $model)
    {

        if ($model::class  != $this->class) return false;

        $this->processing_que[] = $model;

        return $this;
    }

    function last_insert_id()
    {

        return $this->conn->last_insert_id;
    }

    function hasChanges(): int
    {

        return $this->conn->affected_rows ? true : false;
    }


    private function _access_clean_properties(model $model)
    {

        $props = get_object_vars($model);

        $c_props = query::properties($model);

        $clean = [];

        $un_clean = ['timestamps_on', 'softdeletes_on', 'created_at', 'last_modified', 'datetime'];

        foreach ($un_clean as $key) if (array_key_exists($key, $props)) unset($props[$key]);

        foreach ($props as $k => $v) if (!in_array($k, array_keys($c_props))) $clean[$k] = $v;

        return $clean;
    }


    private function update($model, $condition)
    {

        $changed = $this->conn->runOperation(
            query::update($model, $condition),
            $this->_access_clean_properties($model)
        );

        return $changed;
    }


    private function save($model)
    {
        $insert = $this->conn->runOperation(
            query::insert($model),
            $this->_access_clean_properties($model)
        );
        return $insert;
    }

    public function reset_que()
    {

        $this->processing_que = [];
    }

    function saveChanges()
    {

        foreach ($this->processing_que as $model) {
            if ($model->id == 0 || $model->id == '') $this->save($model);
            if ($model->id != 0 || $model->id != '') $this->update($model, ['id' => ":id"]);
        }
        $this->reset_que();

        return $this;
    }

    function getList($columns = [], array $sorting_and_limit = null)
    {

        $this->conn->runOperation(query::all($this->model, $columns, $sorting_and_limit));

        return $this->conn->results($this->class);
    }


    function destroy($condition){

        $this->conn->runOperation(query::delete($this->model), $condition);

    }

    function delete()
    {
            foreach ($this->processing_que as $model) $this->conn->runOperation(query::delete($model), ['id' => $model->id]);

            $this->reset_que();
    }


    public function findById(int $id)
    {

        $this->conn->runOperation(query::findById($this->model), ['id' => $id]);

        $results = $this->conn->results($this->class);

        return count($results)  == 1 ? $results[0] : null;
    }


    public function find(array $filters, $columns = [], $parameters = null, array $sorting_and_limit = null)
    {

        $this->conn->runOperation(query::find($this->model, $columns, $filters, $sorting_and_limit), $parameters);

        return $this->conn->results($this->class);
    }


    public function count($condition, array $parameters = null): int
    {

        $this->conn->runOperation(query::find($this->model::class, $condition, ['COUNT(`id`) as matches']), $parameters);

        $results = $this->conn->results(stdClass::class);

        return count($results) == 1 ? $results[0]->matches : false;
    }


    public function findByIdAsResource($resource ,int $id ){

        $this->conn->runOperation(query::findById($this->model::class),['id' => $id]);

        $results = $this->conn->results($resource);

        return count($results)  == 1 ? $results[0]->data : null;

    }


    public function getListAsResources($resource,$sorting_and_limit){

        $this->conn->runOperation(query::all($this->model,['*'], $sorting_and_limit));

        $results = $this->conn->results($resource);

        return count($results)  == 1 ? $results[0]->data : null;

    }

    public function findAsResources($resource, array $filters, $parameters = null, array $sorting_and_limit = null)
    {

        $this->conn->runOperation(query::find($this->model,["*"], $filters, $sorting_and_limit), $parameters);

        return $this->conn->results($resource);
    }
}
