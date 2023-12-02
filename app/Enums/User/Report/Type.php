<?php

namespace App\Enums\User\Report;

final class Type
{
    public const POST = 'post';
    public const COMMENT = 'comment';

    public const ALL = [
        self::POST,
        self::COMMENT,
    ];
}