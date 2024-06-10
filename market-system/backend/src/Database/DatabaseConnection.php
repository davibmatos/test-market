<?php
namespace App\Database;

use PDO;
use Dotenv\Dotenv;

class DatabaseConnection {
    private static $instance = null;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::initInstance();
        }
        return self::$instance;
    }

    public static function setInstance(PDO $instance) {
        self::$instance = $instance;
    }

    private static function initInstance() {
        Dotenv::createImmutable(__DIR__ . '/../..')->load();
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_NAME'];
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            self::$instance = new PDO("mysql:host=$host;port=3306;dbname=$db", $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    private function __clone() {}
    public function __wakeup() {}
}
