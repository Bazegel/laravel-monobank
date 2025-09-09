<?php
declare(strict_types=1);

namespace Bazegel\Monobank;

use GuzzleHttp\Client;
use Bazegel\Monobank\DTO\Response;
use Bazegel\Monobank\Enums\HttpMethod;
use Bazegel\Monobank\Interfaces\HttpClient;

class MonobankClient implements HttpClient
{
    /**
     * @var Client
     */
    protected $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function request(string $method, string $url, array $data = []): Response
    {
        if (($method === HttpMethod::GET || $method === HttpMethod::DELETE) && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        if ($method === HttpMethod::POST && !empty($data)) {
            $requestData = [
                'json' => $data,
            ];
        }

        $response = $this->httpClient->request($method, $url, $requestData ?? []);

        return new Response(
            json_decode($response->getBody()->getContents(), true),
            $response->getStatusCode()
        );
    }
}
