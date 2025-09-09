<?php
declare(strict_types=1);

namespace Bazegel\Monobank\Interfaces;

use Bazegel\Monobank\DTO\Response;

interface HttpClient
{
    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @return Response
     */
    public function request(string $method, string $url, array $data = []): Response;
}
