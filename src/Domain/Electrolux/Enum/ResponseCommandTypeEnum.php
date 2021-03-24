<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Enum;

use MyCLabs\Enum\Enum;

class ResponseCommandTypeEnum extends Enum
{
    public const ADD_DEVICE = 'putDevice';
    public const ASK = 'ask';
    public const CHANGE_CALENDAR_SLOTS = 'changeTimeSlots';
    public const CHANGE_DEVICE = 'changedDeviceParams';
    public const DELETE_DEVICE = 'deviceDelete';
    public const GET_CALENDAR_SLOTS = 'getTimeSlots';
    public const GET_DEVICE_STATISTIC = 'getDeviceStat';
    public const GET_DEVICES = 'getDeviceParams';
    public const NEW_DEVICE = 'deviceAdded';
    public const SET_CALENDAR = 'SetTimeSlot';
    public const SET_DEFAULT_DEVICE_PARAMS = 'setDefaultDeviceParams';
    public const TOKEN = 'token';
    public const UPDATE_DEVICE = 'deviceUpdate';
    public const WAITING_DEVICE = 'deviceAvaiting';
}
