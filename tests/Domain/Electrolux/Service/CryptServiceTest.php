<?php

declare(strict_types=1);

namespace App\Test\Domain\Electrolux\Service;

use App\Domain\Electrolux\Service\CryptService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class CryptServiceTest extends TestCase
{
    /**
     * @dataProvider encryptData
     */
    public function testEncrypt(string $message, string $key, string $expected): void
    {
        $service = new CryptService($key);

        self::assertEquals($expected, $service->encrypt($message));
    }

    /**
     * @return string[][]
     */
    public function encryptData(): array
    {
        return [
            [
                '{"lang":"ru","command":"getTimeSlots","data":{}}',
                '+gv9fGmmskc6QqhxZ2+3Juau43uPWxqvR4Ps++h4G24=',
                '4xUSzydJBMlzVsr+IDEM1uS0Kq/7gHV+AHTj3Bt2qfvomkE9X2v/Z+QrU3svUVpzK/W7TlC68kUeAaGNuJeFpw==4f5d27f2f1629d79e979cd9ee76a7a8c',
            ],
            [
                '{"message_id":"5","command":"token","result":{"result":"93938"},"message":""}',
                '+gv9fGmmskc6QqhxZ2+3Juau43uPWxqvR4Ps++h4G24=',
                'dRvExXJrlBNrzFtyaoVXGCytA28fR2GmA9MtFAD6Q9I3dnqkGwNamDUUXrwDSBS0lmwgqzCcDG9l4lWma2VBgYzIukZxDpEwdt8nK07BOlg=a75b8d18b5b97167e1cb109f31092065',
            ],
            [
                '{"message_id":"a","command":"token","result":{"result":"93938"},"message":""}',
                'aDgJg2hy0KIs2Re9wTiEpJ/YbC1slvdN4ch1A12ZEEk=',
                'XCUFdy+zMmNpiQsN1C7Aw/qvUQpje+bS1p3EBzdSyxr2OgN1+DKzsYPihjjXijkBpCz4AEeJxT+Q0vDiU3MlVGa1WK147oPZURNX/rFIQnE=f03bf2ee805807d756c8269a0fa699fe',
            ],
        ];
    }

    /**
     * @dataProvider decryptData
     */
    public function testDecrypt(string $message, string $key, string $expected): void
    {
        $service = new CryptService($key);

        self::assertEquals($expected, $service->decrypt($message));
    }

    /**
     * @return string[][]
     */
    public function decryptData(): array
    {
        return [
            [
                '4xUSzydJBMlzVsr+IDEM1uS0Kq/7gHV+AHTj3Bt2qfvomkE9X2v/Z+QrU3svUVpzK/W7TlC68kUeAaGNuJeFpw==4f5d27f2f1629d79e979cd9ee76a7a8c',
                '+gv9fGmmskc6QqhxZ2+3Juau43uPWxqvR4Ps++h4G24=',
                '{"lang":"ru","command":"getTimeSlots","data":{}}',
            ],
            [
                'dRvExXJrlBNrzFtyaoVXGCytA28fR2GmA9MtFAD6Q9I3dnqkGwNamDUUXrwDSBS0lmwgqzCcDG9l4lWma2VBgYzIukZxDpEwdt8nK07BOlg=a75b8d18b5b97167e1cb109f31092065',
                '+gv9fGmmskc6QqhxZ2+3Juau43uPWxqvR4Ps++h4G24=',
                '{"message_id":"5","command":"token","result":{"result":"93938"},"message":""}',
            ],
            [
                'XCUFdy+zMmNpiQsN1C7Aw/qvUQpje+bS1p3EBzdSyxr2OgN1+DKzsYPihjjXijkBpCz4AEeJxT+Q0vDiU3MlVGa1WK147oPZURNX/rFIQnE=f03bf2ee805807d756c8269a0fa699fe',
                'aDgJg2hy0KIs2Re9wTiEpJ/YbC1slvdN4ch1A12ZEEk=',
                '{"message_id":"a","command":"token","result":{"result":"93938"},"message":""}',
            ],
        ];
    }
}
