<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static self CHANGE_PASSWORD()
 * @method static self CREATE_CALENDAR()
 * @method static self DELETE_DEVICE()
 * @method static self DELETE_DEVICE_BY_TEMP_ID()
 * @method static self LOGIN()
 * @method static self REGISTRATION()
 * @method static self REMIND_PASSWORD()
 * @method static self SEND_CODE()
 * @method static self UPDATE_CALENDAR_SLOTS()
 */
class RestApiEnum extends Enum
{
    public const CHANGE_PASSWORD = 'api/userChangePassword';
    public const CREATE_CALENDAR = 'api/setTimeSlot';
    public const DELETE_DEVICE = 'api/deleteDevice';
    public const DELETE_DEVICE_BY_TEMP_ID = 'api/deleteDeviceByTempID';
    public const LOGIN = 'api/userAuth';
    public const REGISTRATION = 'api/userRegister';
    public const REMIND_PASSWORD = 'api/userRemindPassword';
    public const SEND_CODE = 'api/userRegister';
    public const UPDATE_CALENDAR_SLOTS = 'api/setTimeSlot';
}
