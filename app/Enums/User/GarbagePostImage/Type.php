<?php

namespace App\Enums\User\GarbagePostImage;

final class Type
{
    public const BEFORE = 'before';
    public const AFTER = 'after';

    public const ALL = [
        self::BEFORE,
        self::AFTER,
    ];
}