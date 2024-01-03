<?php

namespace App\Enums\Report;

final class Type
{
    public const POST = 'GarbagePost';
    public const COMMENT = 'PostComment';
    public const POSTSHARE = 'PostShare';

    public const ALL = [
        self::POST,
        self::COMMENT,
        self::POSTSHARE
    ];
}