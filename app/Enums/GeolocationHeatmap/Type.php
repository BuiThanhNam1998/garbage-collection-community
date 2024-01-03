<?php

namespace App\Enums\GeolocationHeatmap;

final class Type
{
    public const STREET = 'street';
    public const CITY = 'city';

    public const ALL = [
        self::STREET,
        self::CITY,
    ];
}