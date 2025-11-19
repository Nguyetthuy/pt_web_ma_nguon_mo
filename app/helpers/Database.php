<?php

class Database {

    private static $connection = null;

    public static function connect() {

        if (self::$connection === null) {
            $config = include __DIR__ . '/../../config/config.php';

            $db = $config['db'];
            $dsn = "mysql:host={$db['host']};dbname={$db['database']};charset={$db['charset']}";

            try {
                self::$connection = new PDO($dsn, $db['username'], $db['password']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}

