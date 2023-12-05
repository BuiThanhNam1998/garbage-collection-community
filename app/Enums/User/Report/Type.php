<?php

namespace App\Enums\User\Report;

final class Type
{
    public const POST = 'GarbagePost';
    public const COMMENT = 'PostComment';

    public const ALL = [
        self::POST,
        self::COMMENT,
    ];
}