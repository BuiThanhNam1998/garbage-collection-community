<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }


    public function create($data)
    {
        return $this->model->create($data);
    }

    public function queryByCondition($condition)
    {
        return $this->model->where($condition);
    }

    public function deleteByCondition($condition)
    {
        return $this->model->where($condition)->delete();;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}