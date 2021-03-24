<?php

declare(strict_types=1);

namespace App\Domain\Electrolux;

use App\Domain\Electrolux\Dto\Http\AuthResultDto;
use App\Domain\Electrolux\Dto\Http\Request\LoginDto;
use App\Domain\Electrolux\Dto\Http\Response\AuthDto;
use App\Domain\Electrolux\Dto\Http\ServerDto;
use App\Domain\Electrolux\Dto\Http\UserDto;
use App\Domain\Electrolux\Enum\RestApiEnum;
use App\Domain\Electrolux\Enum\ResultTypeEnum;
use App\Domain\Electrolux\Exception\HttpResponseException;
use App\Domain\Electrolux\Helper\Json;
use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class HttpClient
{
    private BaseClient $httpClient;

    public function __construct(private Config $config)
    {
        $this->httpClient = new BaseClient([
            'base_uri' => $config->getUri(),
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws HttpResponseException
     * @throws JsonException
     */
    public function login(LoginDto $dto): AuthResultDto
    {
        $response = $this->httpClient->post(RestApiEnum::LOGIN, [
            'body' => Json::encode($dto),
            'headers' => [
                'lang' => $this->config->getLang(),
                'tcp' => 'y',
                'debug' => 'new',
                'Content-Type' => 'application/json; charset=UTF-8',
                'Connection' => 'Keep-Alive',
                'Accept-Encoding' => 'gzip',
                'User-Agent' => 'okhttp/4.3.1',
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new HttpResponseException('Error request', $response);
        }

        try {
            $result = $this->buildAuthResponse(Json::decodeAsArray($response->getBody()->getContents()));
        } catch (JsonException $e) {
            throw new HttpResponseException($e->getMessage(), $response);
        }

        if ($result->getResultType()->equals(ResultTypeEnum::ERROR())) {
            throw new HttpResponseException($result->getErrorMessage(), $response);
        }

        return $result->getResult();
    }

    private function buildAuthResponse(array $data): AuthDto
    {
        $dto = new AuthDto((int) $data['error_code'], $data['error_message']);

        if ($dto->getResultType()->equals(ResultTypeEnum::SUCCESS())) {
            $dto->setResult($this->buildAuthResultDto($data['result']));
        }

        return $dto;
    }

    private function buildAuthResultDto(array $data): AuthResultDto
    {
        $dto = new AuthResultDto();
        $dto->setToken($data['token'])
            ->setEncKey($data['enc_key'])
            ->setUser(new UserDto($data['user']['name']))
            ->setServer(new ServerDto($data['server']['host'], $data['server']['port']))
            ->setTcpServer(new ServerDto($data['tcp_server']['host'], $data['tcp_server']['port']))
            ->setVersions($data['versions'])
        ;

        return $dto;
    }
}
