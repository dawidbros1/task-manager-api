<?php

declare(strict_types=1);

namespace Model\General;

use Model\General\Response;

class Config
{
    private $config;

    public function __construct($configuration)
    {
        $this->config = $configuration;
        $this->response = new Response();
    }

    public function get($path)
    {
        $output = $this->config;
        $array = explode(".", $path);

        foreach ($array as $name) {

            if (array_key_exists($name, $output)) {
                $output = $output[$name];
            } else {
                $this->response->error(506, 'Configuration error - klucz [' . $name . '] nie istnieje');
            }
        }

        return $output;
    }
}
