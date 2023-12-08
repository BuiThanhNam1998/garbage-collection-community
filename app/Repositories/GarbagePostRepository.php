<?php 

namespace App\Repositories;

use App\Enums\User\GarbagePost\Location\Type;
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

    public function queryByCountry($countryId, $cityIds) 
    {
        return $this->queryApprovePost()
            ->where(function($q) use ($countryId, $cityIds) {
                $q->where(function($q) use ($countryId) {
                    $q->where('locationable_type', Type::COUNTRY)
                        ->where('locationable_id', $countryId);
                })->orWhere(function($q) use ($cityIds) {
                    $q->where('locationable_type', Type::CITY)
                    ->whereIn('locationable_id', $cityIds);
                });
            });
    }

    public function queryByLocation($locationType, $locationId) 
    {
        return $this->queryApprovePost()
            ->where('locationable_type', $locationType)
            ->where('locationable_id', $locationId);
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
}
