<?php

namespace Core;

use PDO;

class Database
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        try {
            $db_host = $_ENV['DB_HOST'];
            $db_name = $_ENV['DB_NAME'];
            $db_user = $_ENV['DB_USER'];
            $db_pass = $_ENV['DB_PASS'];

            $this->connection = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (\PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            throw new \Exception('Error connecting to the database');
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
