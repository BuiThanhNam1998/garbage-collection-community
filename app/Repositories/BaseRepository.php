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

    public function all()
    {
        return $this->model->all();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($data)
    {
        return $this->model->update($data);
    }

    public function updateOrCreate($condition, $data)
    {
        return $this->model->updateOrCreate($condition, $data);
    }

    public function updateByCondition($condition, $data)
    {
        return $this->model->where($condition)->update($data);
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

    public function count()
    {
        return $this->model->count();
    }

    public function last()
    {
        return $this->model->latest()->first();
    }

    public function queryWithDetail()
    {
        return $this->model->with(['userDetail']);
    }

    public function updateByIds($ids, $data)
    {
        return $this->model->whereIn('id', $ids)->update($data);
    }

    public function insert($data)
    {
        return $this->model->insert($data);
    }
}