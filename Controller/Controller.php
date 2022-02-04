<?php

namespace Controller;

use Model\Database;
use Model\Response;
use Helper\Request;

abstract class Controller
{
    protected static $config;

    protected Request $request;
    protected Response $response;

    protected $hashMethod;

    public static function initConfiguration($config): void
    {
        self::$config = $config;
    }

    public function __construct($action)
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->action = $action;

        if (empty(self::$config->get('db'))) {
            // throw new ConfigurationException('Configuration error');
            exit("Configuration error");
        }

        Database::initConfiguration(self::$config->get('db'));
        $this->hashMethod = self::$config->get('hash.method');
    }

    public function run()
    {
        if (!method_exists($this, $this->action . "Action")) {
            $className = (new \ReflectionClass($this))->getShortName();
            $this->response->error(404, "Action [" . $this->action . "] doesn't exists in " . $className);
            $this->response->send();
        }

        $action = $this->action . "Action";
        $this->$action();
        $this->response->send();
    }

    protected function hash($param, $method = null)
    {
        return hash($method ?? $this->hashMethod, $param);
    }
}
