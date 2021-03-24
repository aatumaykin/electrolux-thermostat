<?php

declare(strict_types=1);

namespace App;

use App\Domain\Electrolux\Helper\Json;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse as BaseResponse;
use function get_class;
use const JSON_ERROR_NONE;
use const JSON_THROW_ON_ERROR;
use const PHP_VERSION_ID;

class JsonResponse extends BaseResponse
{
    public function setData($data = []): string
    {
        try {
            $data = Json::encode($data, $this->encodingOptions);
        } catch (Exception $e) {
            if ('Exception' === get_class($e) && 0 === strpos($e->getMessage(), 'Failed calling ')) {
                throw $e->getPrevious() ?: $e;
            }

            throw $e;
        }

        if (PHP_VERSION_ID >= 70300 && (JSON_THROW_ON_ERROR & $this->encodingOptions)) {
            return $this->setJson($data);
        }

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(json_last_error_msg());
        }

        return $this->setJson($data);
    }
}
