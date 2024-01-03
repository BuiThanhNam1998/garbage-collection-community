<?php

namespace App\Enums\Poll;

final class Status
{
    public const DRAFT = 'draft';
    public const PUBLISHED = 'published';

    public const ALL = [
        self::DRAFT,
        self::PUBLISHED,
    ];
}