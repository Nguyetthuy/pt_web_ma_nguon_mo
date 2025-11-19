<?php
class MCQ {
    public static function allByCourse($course_id) {
        $pdo = db_connect();
        $stmt = $pdo->prepare("SELECT * FROM questions WHERE course_id = ? ORDER BY id ASC");
        $stmt->execute([$course_id]);
        return $stmt->fetchAll();
    }
}
