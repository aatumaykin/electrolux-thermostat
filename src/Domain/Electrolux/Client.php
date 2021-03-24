<?php

declare(strict_types=1);

namespace App\Domain\Electrolux;

use App\Domain\Electrolux\Dto\LoginResponseDto;
use App\Domain\Electrolux\Dto\Request\LoginDto;
use App\Domain\Electrolux\Enum\RestApiEnum;
use App\Domain\Electrolux\Exception\HttpResponseException;
use App\Domain\Electrolux\Helper\Json;
use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use JsonException;
use RuntimeException;

class Client
{
    private BaseClient $httpClient;
    private Config $config;

    public function __construct(Config $config)
    {
        $this->httpClient = new BaseClient([
            'base_uri' => $config->getUri(),
        ]);
        $this->config = $config;
    }

    /**
     * @throws GuzzleException
     * @throws HttpResponseException
     * @throws JsonException
     */
    public function login(LoginDto $dto): LoginResponseDto
    {
        throw new HttpResponseException('Error request', new Response());
        var_dump(Json::encode($dto));exit;
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

        $data = Json::decodeAsArray($response->getBody()->getContents());

        if ('0' !== $data['error_code']) {
            throw new RuntimeException($data['error_message']);
        }

        return LoginResponseDto::fromResponseDto($data['result']);
    }
}
