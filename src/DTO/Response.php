<?php
declare(strict_types=1);

namespace Bazegel\Monobank\DTO;

class Response
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var int
     */
    public $statusCode;

    /**
     * @param array $data
     * @param int $statusCode
     */
    public function __construct(
        array $data,
        int $statusCode
    )
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param string $key
     * @param $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
}
