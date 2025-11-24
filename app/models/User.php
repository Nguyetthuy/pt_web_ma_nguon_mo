<?php
require_once __DIR__ . '/../helpers/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findUserByEmail($email) {
        $sql = "SELECT * FROM `user` WHERE email = :email LIMIT 1";
        $rows = $this->db->runQuery($sql, [':email' => $email]);
        return $rows[0] ?? false;
    }

    // $data = ['user_name','email','phone','password','avatar']
    public function register($data) {
        try {
            if ($this->findUserByEmail($data['email'])) {
                return 'Email này đã được sử dụng.';
            }

            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO `user` (user_name, email, phone, password, role, avatar)
                    VALUES (:user_name, :email, :phone, :password, :role, :avatar)";

            $params = [
                ':user_name' => $data['user_name'],
                ':email'     => $data['email'],
                ':phone'     => $data['phone'] ?: null,
                ':password'  => $hashed,
                ':role'      => $data['role'] ?? 'student',
                ':avatar'    => $data['avatar'] ?? null
            ];

            $ok = $this->db->execute($sql, $params);
            if ($ok) {
                return true;
            }
            return 'Không thể ghi vào cơ sở dữ liệu.';
        } catch (PDOException $e) {
            error_log('User::register PDOException: ' . $e->getMessage());
            return $e->getMessage();
        } catch (Exception $e) {
            error_log('User::register Exception: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    // tùy dùng ở nơi khác
    public function createUser($user_name, $email, $phone, $password, $role = 'student', $avatar = null) {
        $sql = "INSERT INTO `user` (user_name, email, phone, password, role, avatar)
                VALUES (:user_name, :email, :phone, :password, :role, :avatar)";
        return $this->db->execute($sql, [
            ':user_name' => $user_name,
            ':email' => $email,
            ':phone' => $phone,
            ':password' => $password,
            ':role' => $role,
            ':avatar' => $avatar
        ]);
    }

     public function authenticate(string $email, string $password)
    {
        $user = $this->findUserByEmail($email);
        if (!$user) {
            return false;
        }

        // Nếu mật khẩu lưu bằng password_hash
        if (!empty($user['password']) && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        // Nếu DB lưu mật khẩu dạng plain (không khuyến nghị) — kiểm tra phụ trợ
        if (!empty($user['password']) && $user['password'] === $password) {
            unset($user['password']);
            return $user;
        }

        return false;
    }

    // Đăng ký user với Google (không cần password)
    public function registerWithGoogle($data) {
        try {
            if ($this->findUserByEmail($data['email'])) {
                return 'Email này đã được sử dụng.';
            }

            // Tạo password ngẫu nhiên hoặc để null (tùy database cho phép)
            // Vì schema yêu cầu password NOT NULL, ta sẽ hash một chuỗi ngẫu nhiên
            $randomPassword = bin2hex(random_bytes(32));
            $hashed = password_hash($randomPassword, PASSWORD_DEFAULT);

            $sql = "INSERT INTO `user` (user_name, email, phone, password, role, avatar)
                    VALUES (:user_name, :email, :phone, :password, :role, :avatar)";

            $params = [
                ':user_name' => $data['user_name'],
                ':email'     => $data['email'],
                ':phone'     => $data['phone'] ?: null,
                ':password'  => $hashed, // Lưu password ngẫu nhiên (user sẽ không dùng password này)
                ':role'      => $data['role'] ?? 'student',
                ':avatar'    => $data['avatar'] ?? null
            ];

            $ok = $this->db->execute($sql, $params);
            if ($ok) {
                return true;
            }
            return 'Không thể ghi vào cơ sở dữ liệu.';
        } catch (PDOException $e) {
            error_log('User::registerWithGoogle PDOException: ' . $e->getMessage());
            return $e->getMessage();
        } catch (Exception $e) {
            error_log('User::registerWithGoogle Exception: ' . $e->getMessage());
            return $e->getMessage();
        }
    }
}