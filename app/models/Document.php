<?php

require_once __DIR__ . '/../helpers/Database.php';

class Document
{
    /**
     * Lấy tất cả tài liệu
     */
    public static function all()
    {
        $db = new Database();
        $sql = "SELECT * FROM documents ORDER BY created_at DESC";
        return $db->runQuery($sql);
    }

    /**
     * Lấy tài liệu theo ID
     */
    public static function find($id)
    {
        $db = new Database();
        $sql = "SELECT * FROM documents WHERE id = ?";
        $result = $db->runQuery($sql, [$id]);
        return $result[0] ?? null;
    }

    /**
     * Tạo tài liệu mới
     */
    public static function create($data)
    {
        $db = new Database();
        $sql = "INSERT INTO documents (title, description, file_name, file_path, file_type, file_size, uploaded_by, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = [
            $data['title'],
            $data['description'],
            $data['file_name'],
            $data['file_path'],
            $data['file_type'],
            $data['file_size'],
            $data['uploaded_by']
        ];
        
        $db->execute($sql, $params);
        return $db->lastInsertId();
    }

    /**
     * Xóa tài liệu
     */
    public static function delete($id)
    {
        $db = new Database();
        $sql = "DELETE FROM documents WHERE id = ?";
        return $db->execute($sql, [$id]);
    }

    /**
     * Cập nhật lượt tải xuống
     */
    public static function incrementDownloads($id)
    {
        $db = new Database();
        $sql = "UPDATE documents SET downloads = downloads + 1 WHERE id = ?";
        return $db->execute($sql, [$id]);
    }
}
