<?php

declare(strict_types=1);

namespace Helper;

class Request
{
    private $get = [];
    private $server = [];

    public function __construct()
    {
        $this->get = $_GET;
        $this->server = $_SERVER;
    }

    // === GET ===
    public function getParam(string $name, $default = null)
    {
        return $this->get[$name] ?? $default;
    }

    public function getParams(array $names)
    {
        $output = [];

        foreach ($names as $name) {
            $output[$name] = $this->getParam($name);
        }

        return $output;
    }

    public function hasGetNames(array $names)
    {
        foreach ($names as $name) {
            if (!isset($this->get[$name])) {
                return false;
            }
        }

        return true;
    }

    // === SERVER ===
    public function isPost(): bool
    {
        return $this->server['REQUEST_METHOD'] === 'POST';
    }

    public function isGet(): bool
    {
        return $this->server['REQUEST_METHOD'] === 'GET';
    }

    public function queryString(): string
    {
        return $this->server['QUERY_STRING'];
    }
}
