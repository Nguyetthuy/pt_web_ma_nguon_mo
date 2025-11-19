<?php
class Teacher {
    public static function all() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM teacher ORDER BY teacher_id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
