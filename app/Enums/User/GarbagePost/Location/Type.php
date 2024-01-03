<?php

namespace App\Enums\User\GarbagePost\Location;

final class Type
{
    public const COUNTRY = 'country';
    public const CITY = 'city';
    public const STREET = 'street';

    public const ALL = [
        self::COUNTRY,
        self::CITY,
        self::STREET,
    ];
}