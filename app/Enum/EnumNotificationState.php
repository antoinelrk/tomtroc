<?php

namespace App\Enum;

enum EnumNotificationState: string
{
    case ERROR = 'error';
    case INFO = 'info';
    case SUCCESS = 'success';
}