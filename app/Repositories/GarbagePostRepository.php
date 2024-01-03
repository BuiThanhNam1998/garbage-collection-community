<?php 

namespace App\Repositories;

use App\Models\GarbagePost;

class GarbagePostRepository extends BaseRepository
{
    public function __construct(GarbagePost $model)
    {
        parent::__construct($model);
    }

    public function queryApprovePost() 
    {
        return $this->model->approved();
    }

    public function queryPendingPost() 
    {
        return $this->model->pending();
    }

    public function queryByIds($ids)
    {
        return $this->model->whereIn('id', $ids);
    }

    public function queryByCountryId($countryId) 
    {
        return $this->queryApprovePost()
            ->join('streets', 'garbage_posts.street_id', '=', 'streets.id')
            ->join('cities', 'streets.city_id', '=', 'cities.id')
            ->join('countries', 'cities.country_id', '=', 'countries.id')
            ->where('countries.id', $countryId);
    }

    public function queryByUserId($userId)
    {
        return $this->model->where('user_id', $userId);
    }

    public function queryByStreetIds($streetIds)
    {
        return $this->model->whereIn('street_id', $streetIds);
    }
}
