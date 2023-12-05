<?php

namespace App\Enums\ModerationQueue;

final class Type
{
    public const POST = 'GarbagePost';
    public const COMMENT = 'PostComment';

    public const ALL = [
        self::POST,
        self::COMMENT,
    ];
}