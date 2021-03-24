<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Enum;

use MyCLabs\Enum\Enum;

class RequestCommandTypeEnum extends Enum
{
    public const ADD_DEVICE = 'putDevice';
    public const GET_CALENDAR_SLOTS = 'getTimeSlots';
    public const GET_DEVICE_STATISTIC = 'getDeviceStat';
    public const GET_DEVICES = 'getDeviceParams';
    public const RESET_DEVICE = 'setDefaultDeviceParams';
    public const TOKEN = 'token';
    public const UPDATE_DEVICE = 'setDeviceParams';
}
