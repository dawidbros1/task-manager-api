<?php

declare(strict_types=1);

namespace Helper;

class Request
{
    private $get = [];

    public function __construct()
    {
        $this->get = $_GET;
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

    public function hasProperties($object, $properties)
    {
        $missingFields = "";

        foreach ($properties as $property) {
            if (!property_exists($object, $property)) {
                $missingFields .= $property . ", ";
                $ok = false;
            }
        }

        if (!$ok) $missingFields = substr($missingFields, 0, -2);
        return [$ok ?? true, $missingFields];
    }
}
