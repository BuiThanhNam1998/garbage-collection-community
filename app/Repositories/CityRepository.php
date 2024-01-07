<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository extends BaseRepository
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }

    public function queryByCountryId($countryId)
    {
        return $this->model->where('country_id', $countryId);
    }
}
