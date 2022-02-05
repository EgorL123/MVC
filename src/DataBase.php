<?php

namespace Core;

use PDO;

class DataBase
{
    private static $instance;
    private $pdo;
    private ?array $log = null;

    public static function getInstance(): ?\Core\DataBase
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function connect(): ?\PDO
    {
        $host = HOST_NAME;
        $dbname = DB_NAME;
        $username = USER_NAME;
        $passw = PASSWORD;
        $port = PORT;

        if (!$this->pdo) {


            $this->pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $passw);
        }

        return $this->pdo;
    }

    public function exec(string $query = '', array $params = [], string $method = '')
    {
        $this->connect();
        $t = microtime(1);
        $query = $this->pdo->prepare($query);
        $ret = $query->execute($params);
        $t = microtime(1) - $t;

        if (!$ret) {
            if ($query->errorCode()) {
                //trigger_error(json_encode($query->errorInfo()));
                throw new \Exception(json_encode($query->errorInfo()));
            }

            return false;
        }

        $this->log[] =
            [
                'query' => $query,
                'time' => $t,
                'method' => $method
            ];
        return $query->rowCount();
    }

    public function fetchAll(string $query = '', array $params = [], string $method = '')
    {
        $this->connect();
        $t = microtime(1);
        $query = $this->pdo->prepare($query);
        $ret = $query->execute($params);
        $t = microtime(1) - $t;
        if (!$ret) {
            if ($query->errorCode()) {
                trigger_error(json_encode($query->errorInfo()));
            }

            return false;
        }

        $this->log[] =
            [
                'query' => $query,
                'time' => $t,
                'method' => $method
            ];
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lastInsertId()
    {
        $this->connect();
        return $this->pdo->lastInsertId();
    }

    /**
     * @return array<mixed, array<string, mixed>>
     */
    public function getLog(): ?array
    {
        return $this->log;
    }

    public function getConnection(): ?\PDO
    {
        return $this->connect();
    }
}
