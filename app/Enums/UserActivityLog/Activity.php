<?php

namespace App\Enums\UserActivityLog;

final class Activity
{
    public const LOGIN = 'login';
    public const LOGOUT = 'logout';
    public const CREATE_POST = 'create post';
    public const UPDATE_POST = 'update post';
    public const DELETE_POST = 'update post';
    public const CREATE_COMMENT = 'create comment';
    public const UPDATE_COMMENT = 'update comment';
    public const DELETE_COMMENT = 'update comment';
    public const REACT = 'react';

    public const ALL = [
        self::LOGIN,
        self::LOGOUT,
        self::CREATE_POST,
        self::UPDATE_POST,
        self::DELETE_POST,
        self::CREATE_COMMENT,
        self::UPDATE_COMMENT,
        self::DELETE_COMMENT,
        self::REACT,
    ];
}