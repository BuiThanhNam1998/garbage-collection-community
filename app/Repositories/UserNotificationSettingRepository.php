<?php

namespace App\Repositories;

use App\Models\UserNotificationSetting;

class UserNotificationSettingRepository extends BaseRepository
{
    public function __construct(UserNotificationSetting $model)
    {
        parent::__construct($model);
    }
}
