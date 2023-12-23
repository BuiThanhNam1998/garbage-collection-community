<?php

namespace App\Enums\ReactionType;

final class Type
{
    public const POSITIVE = 'positive';
    public const NEGATIVE = 'negative';

    public const ALL = [
        self::POSITIVE,
        self::NEGATIVE,
    ];
}