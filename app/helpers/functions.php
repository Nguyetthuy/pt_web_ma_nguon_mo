<?php
function view($path, $data = []) {
    extract($data);
    include __DIR__ . '/../../resources/views/' . $path . '.php';
}

function db_connect() {
    $cfg = require __DIR__ . '/../../config/config.php';
    $d = $cfg['db'];
    $dsn = "mysql:host={$d['host']};dbname={$d['database']};port={$d['port']};charset={$d['charset']}";
    try {
        $pdo = new PDO($dsn, $d['username'], $d['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die('DB connection failed: ' . $e->getMessage());
    }
}

function asset($path) {
    return '/public/assets/' . ltrim($path, '/');
}
