<?php

namespace App\Enums\Social;

final class Provider
{
    public const GOOGLE = 'google';
    public const FACEBOOK = 'facebook';
    public const TIKTOK = 'rejected';

    public const ALL = [
        self::GOOGLE,
        self::FACEBOOK,
        self::TIKTOK,
    ];
}