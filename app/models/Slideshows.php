<?php
require_once __DIR__ . '/../helpers/Database.php';
class Slideshows {
    public static function all()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM slideshows ORDER BY slide_id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}