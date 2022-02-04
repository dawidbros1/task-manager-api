<?php

namespace Model;

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
        // try {
        $this->validateConfig(self::$config);
        $this->createConnection(self::$config);
        // } catch (PDOException $e) {
        //     throw new StorageException('Connection error');
        // }
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
            // throw new ConfigurationException('Storage configuration error');
        }
    }

    // public function __construct()
    // {
    // try {
    //     $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);

    //     if (mysqli_connect_errno()) {
    //         throw new Exception("Could not connect to database.");
    //     }
    // } catch (Exception $e) {
    //     throw new Exception($e->getMessage());
    // }
    // }

    public function select($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return $result;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return false;
    }

    private function executeStatement($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);

            if ($stmt === false) {
                throw new \Exception("Unable to do prepared statement: " . $query);
            }

            if ($params) {
                $stmt->bind_param($params[0], $params[1]);
            }

            $stmt->execute();

            return $stmt;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
