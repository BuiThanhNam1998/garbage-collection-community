<?php

namespace App\Enums\EducationResource;

final class Status
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';

    public const ALL = [
        self::ACTIVE,
        self::INACTIVE,
    ];
}