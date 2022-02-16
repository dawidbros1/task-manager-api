<?php

namespace Model\General;

use Exception;

use Model\General\Response;

class Database
{
    protected $pdo;
    protected static $config;

    protected $connection = null;

    public static function initConfiguration($config): void
    {
        self::$config = $config;
    }

    public function __construct()
    {
        $this->response = new Response();

        $this->validateConfig(self::$config);

        try {
            $this->createConnection(self::$config);
        } catch (Exception $e) {
            $this->response->error(500, 'Database error - wystąpił problem podczas łączenia z bazą danych');
        }
    }

    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $this->pdo = new \PDO($dsn, $config['user'], $config['password'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    }

    private function validateConfig(array $config): void
    {
        if (
            empty($config['database']) ||
            empty($config['host']) ||
            empty($config['user']) ||
            !isset($config['password'])
        ) {
            $this->response->error(506, 'Configuration error - wymagane klucze to [database | host | user | password]');
        }
    }
}
