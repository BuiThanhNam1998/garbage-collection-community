<?php

namespace App\Enums\User\GarbagePost\Reaction;

final class Type
{
    public const LIKE = 'like';
    public const HEART = 'heart';
    public const UPVOTE = 'upvote';
    public const DOWNVOTE = 'downvote';

    public const ALL = [
        self::LIKE,
        self::HEART,
        self::UPVOTE,
        self::DOWNVOTE,
    ];
}