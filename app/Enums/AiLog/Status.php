<?php

namespace App\Enums\AiLog;

final class Status
{
    public const SUCCESS = 'sucess';
    public const FAILURE = 'failure';

    public const ALL = [
        self::SUCCESS,
        self::FAILURE,
    ];
}