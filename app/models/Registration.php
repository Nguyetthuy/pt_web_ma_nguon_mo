<?php
require_once __DIR__ . '/../helpers/Database.php';

class Registration {
    public static function create($data) {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO registrations (reg_id, course_id, name, email, reg_date, status ) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['reg_id'],
            $data['course_id'],
            $data['name'],
            $data['email'],
            $data['reg_date'],
            $data['status']
        ]);
    }
}