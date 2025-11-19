<?php
/*require_once __DIR__ . '/../helpers/Database.php';

class course {
    public static function all()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM course ORDER BY course_id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM course WHERE course_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}*/