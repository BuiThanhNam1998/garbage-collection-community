<?php

namespace App\Enums\Feedback;

final class Status
{
    public const PENDING = 'pending';
    public const ADDRESSED = 'addressed';

    public const ALL = [
        self::PENDING,
        self::ADDRESSED,
    ];
}