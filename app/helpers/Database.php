<?php

class Database {
    private static ?PDO $pdo = null;

    public function __construct()
    {
        if (!self::$pdo) {
            $host = '127.0.0.1';
            $db   = 'flyhighenglish'; // đảm bảo trùng với phpMyAdmin
            $user = 'root';
            $pass = ''; // thay nếu bạn có password
            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            self::$pdo = new PDO($dsn, $user, $pass, $options);
        }
    }

    // SELECT trả về mảng
    public function runQuery(string $sql, array $params = []): array
    {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // INSERT/UPDATE/DELETE trả về boolean
    public function execute(string $sql, array $params = []): bool
    {
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function lastInsertId(): string
    {
        return self::$pdo->lastInsertId();
    }

    public function getPdo(): PDO
    {
        return self::$pdo;
    }
}

// Kết nối cơ sở dữ liệu
function connect() {
    return Database::connect();
}

?>

