# 📸 Hướng dẫn: Cách xử lý đổi Avatar

## 🔄 Quy trình đổi Avatar

```
User click vào avatar
    ↓
Mở file picker (chọn ảnh)
    ↓
JavaScript tự động submit form
    ↓
POST đến: index.php?route=profile&action=update_avatar
    ↓
UserController::updateAvatar()
    ↓
1. Validate file (size, type)
    ↓
2. Lấy user_id
    ↓
3. Xóa avatar cũ (nếu có)
    ↓
4. Lưu file mới: user_id.extension
    ↓
5. Cập nhật Database
    ↓
6. Cập nhật Session
    ↓
Redirect về trang profile
```

---

## 📁 Các file liên quan

### 1. **Frontend: `resources/views/user/profile.php`**

#### Form upload avatar:
```php
<form action="index.php?route=profile&action=update_avatar" 
      method="POST" 
      enctype="multipart/form-data" 
      id="avatar_form">
    <input type="file" name="avatar" id="avatar_upload" accept="image/*" style="display: none;">
</form>
```

#### JavaScript tự động submit:
```javascript
document.getElementById('avatar_upload').addEventListener('change', function() {
    if (this.files.length > 0) {
        // Tự động submit form khi chọn file
        document.getElementById('avatar_form').submit();
    }
});
```

**Cách hoạt động:**
- User click vào avatar → Mở file picker
- Chọn file → JavaScript tự động submit form
- Form POST đến server với file upload

---

### 2. **Router: `public/index.php`**

```php
case 'profile':
    $controller = new UserController();
    
    // Kiểm tra action update_avatar
    if (isset($_GET['action']) && $_GET['action'] === 'update_avatar' && $method === 'POST') {
        $controller->updateAvatar();  // Xử lý upload
    } else {
        $controller->showProfile();   // Hiển thị trang profile
    }
    break;
```

**Cách hoạt động:**
- Route: `profile` → Hiển thị trang profile
- Route: `profile&action=update_avatar` (POST) → Xử lý upload

---

### 3. **Controller: `app/controllers/UserController.php`**

#### Method `updateAvatar()` - Xử lý upload

**Bước 1: Kiểm tra đăng nhập**
```php
if (!isset($_SESSION['user'])) {
    // Redirect về login
}
```

**Bước 2: Validate file upload**
```php
// Kiểm tra có file không
if (!isset($_FILES['avatar'])) {
    // Lỗi: Không có file
}

// Kiểm tra lỗi upload
if ($file['error'] !== UPLOAD_ERR_OK) {
    // Lỗi: Upload failed
}
```

**Bước 3: Validate kích thước và định dạng**
```php
$max_size = 5 * 1024 * 1024; // 5 MB
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

// Kiểm tra size
if ($file['size'] > $max_size) {
    // Lỗi: File quá lớn
}

// Kiểm tra type
if (!in_array($file['type'], $allowed_types)) {
    // Lỗi: Định dạng không hợp lệ
}
```

**Bước 4: Lấy user_id**
```php
$user_id = $_SESSION['user']['user_id'] ?? null;

// Nếu không có trong session, lấy từ database
if (!$user_id) {
    $user = $this->userModel->findUserByEmail($_SESSION['user']['email']);
    $user_id = $user['user_id'];
    $_SESSION['user']['user_id'] = $user_id;
}
```

**Bước 5: Xóa avatar cũ (nếu có)**
```php
$old_files = glob($upload_dir . $user_id . '.*');
foreach ($old_files as $old_file) {
    if (is_file($old_file)) {
        @unlink($old_file);  // Xóa file cũ
    }
}
```

**Bước 6: Lưu file mới**
```php
// Tên file: user_id.extension (ví dụ: 1.jpg, 5.png)
$new_file_name = $user_id . '.' . $file_extension;
$destination = $upload_dir . $new_file_name;

// Di chuyển file từ temp vào thư mục upload
move_uploaded_file($file['tmp_name'], $destination);
```

**Bước 7: Cập nhật Database**
```php
$public_path = $base . '/uploads/avatars/' . $new_file_name;
$result = $this->userModel->updateAvatar($_SESSION['user']['email'], $public_path);
```

**Bước 8: Cập nhật Session**
```php
if ($result) {
    $_SESSION['user']['avatar'] = $public_path;
    $_SESSION['success_message'] = 'Ảnh đại diện đã được cập nhật thành công!';
}
```

---

### 4. **Model: `app/models/User.php`**

#### Method `updateAvatar()` - Cập nhật database

```php
public function updateAvatar($email, $avatarPath) {
    try {
        $sql = "UPDATE `user` SET avatar = :avatar WHERE email = :email";
        $params = [
            ':avatar' => $avatarPath,
            ':email' => $email
        ];

        $ok = $this->db->execute($sql, $params);
        return $ok;
    } catch (PDOException $e) {
        error_log('User::updateAvatar PDOException: ' . $e->getMessage());
        return false;
    }
}
```

**SQL Query:**
```sql
UPDATE `user` 
SET avatar = '/flyhighenglish/public/uploads/avatars/1.jpg' 
WHERE email = 'user@example.com'
```

---

## 📂 Cấu trúc thư mục

```
public/
  └── uploads/
      └── avatars/
          ├── 1.jpg    (Avatar của user_id = 1)
          ├── 2.png   (Avatar của user_id = 2)
          └── 5.gif   (Avatar của user_id = 5)
```

**Quy tắc đặt tên:**
- Format: `user_id.extension`
- Ví dụ: User có `user_id = 1` upload file PNG → `1.png`
- Mỗi user chỉ có 1 file avatar (file mới thay thế file cũ)

---

## ✅ Validation Rules

| Kiểm tra | Giá trị | Thông báo lỗi |
|----------|---------|---------------|
| **Kích thước** | Tối đa 5MB | "Kích thước file quá lớn (tối đa 5MB)." |
| **Định dạng** | JPG, PNG, GIF | "Chỉ chấp nhận định dạng JPG, PNG hoặc GIF." |
| **File upload** | Phải có file | "Không nhận được file upload." |
| **Đăng nhập** | Phải đăng nhập | "Vui lòng đăng nhập để cập nhật avatar." |

---

## 🔐 Bảo mật

1. **Kiểm tra đăng nhập:** Chỉ user đã đăng nhập mới upload được
2. **Validate file type:** Chỉ chấp nhận ảnh (JPG, PNG, GIF)
3. **Validate file size:** Giới hạn 5MB
4. **Tên file an toàn:** Dùng user_id (số) thay vì tên file gốc
5. **Xóa file cũ:** Tự động xóa để tránh tích lũy

---

## 🎯 Luồng xử lý chi tiết

### **Frontend (profile.php)**

1. **HTML:**
   ```html
   <label for="avatar_upload">
       <img src="avatar.jpg"> <!-- Avatar hiện tại -->
       <div class="badge"><i class="fas fa-camera"></i></div>
   </label>
   <input type="file" id="avatar_upload" style="display: none;">
   ```

2. **JavaScript:**
   ```javascript
   // Khi chọn file → Tự động submit
   avatar_upload.addEventListener('change', function() {
       if (this.files.length > 0) {
           avatar_form.submit();
       }
   });
   ```

### **Backend (UserController.php)**

1. **Nhận request POST:**
   ```
   POST /index.php?route=profile&action=update_avatar
   Content-Type: multipart/form-data
   File: avatar (binary)
   ```

2. **Xử lý:**
   - Validate → Lưu file → Cập nhật DB → Cập nhật Session

3. **Response:**
   ```
   Redirect: /index.php?route=profile
   Session: success_message = "Ảnh đại diện đã được cập nhật thành công!"
   ```

---

## 🐛 Xử lý lỗi

| Lỗi | Nguyên nhân | Giải pháp |
|-----|-------------|-----------|
| "Không nhận được file upload" | Form không có file | Kiểm tra `enctype="multipart/form-data"` |
| "Kích thước file quá lớn" | File > 5MB | Nén ảnh hoặc chọn file nhỏ hơn |
| "Chỉ chấp nhận định dạng..." | File không phải ảnh | Chọn file JPG/PNG/GIF |
| "Không thể lưu file" | Quyền thư mục | Set quyền 777 cho `uploads/avatars/` |
| "Không thể cập nhật database" | Lỗi SQL | Kiểm tra kết nối database |

---

## 📝 Tóm tắt

1. **User click avatar** → Mở file picker
2. **Chọn file** → JavaScript tự động submit
3. **Server nhận file** → Validate (size, type)
4. **Xóa avatar cũ** → Tránh tích lũy file
5. **Lưu file mới** → `user_id.extension`
6. **Cập nhật DB** → Lưu đường dẫn avatar
7. **Cập nhật Session** → Hiển thị ngay
8. **Redirect** → Về trang profile với thông báo thành công

**Kết quả:** Avatar được cập nhật và hiển thị ngay lập tức!

