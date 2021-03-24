<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static self ADD_DEVICE()
 * @method static self ASK()
 * @method static self CHANGE_CALENDAR_SLOTS()
 * @method static self CHANGE_DEVICE()
 * @method static self DELETE_DEVICE()
 * @method static self GET_CALENDAR_SLOTS()
 * @method static self GET_DEVICE_STATISTIC()
 * @method static self GET_DEVICES()
 * @method static self NEW_DEVICE()
 * @method static self SET_CALENDAR()
 * @method static self SET_DEFAULT_DEVICE_PARAMS()
 * @method static self TOKEN()
 * @method static self UPDATE_DEVICE()
 * @method static self WAITING_DEVICE()
 * @method static self NULL_COMMAND()
 */
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

    public const NULL_COMMAND = null;
}
