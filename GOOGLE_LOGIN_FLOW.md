# 📖 Giải thích chi tiết: Quy trình đăng nhập bằng Google và lưu vào Database

## 🔄 Tổng quan quy trình

```
Người dùng click "Đăng nhập bằng Google"
    ↓
1. google-login.php → Chuyển hướng đến Google
    ↓
2. Google xác thực người dùng
    ↓
3. Google trả về code → google-callback.php
    ↓
4. google-callback.php đổi code lấy access_token
    ↓
5. Lấy thông tin user từ Google API
    ↓
6. Kiểm tra email đã tồn tại trong DB chưa?
    ├─ CÓ → Đăng nhập (lưu vào session)
    └─ CHƯA → Đăng ký mới → Lưu vào DB → Đăng nhập
```

---

## 📁 Các file liên quan

### 1. **config/config.php** - Cấu hình Google OAuth
```php
'google' => [
    'client_id' => '...',        // ID ứng dụng từ Google Console
    'client_secret' => '...',    // Secret key (bảo mật)
    'redirect_uri' => '...'      // URL Google sẽ gửi code về
]
```

### 2. **public/google-login.php** - Bước khởi đầu
### 3. **public/google-callback.php** - Xử lý callback từ Google
### 4. **app/models/User.php** - Model xử lý database

---

## 🔍 Chi tiết từng bước

### **BƯỚC 1: Người dùng click "Đăng nhập bằng Google"**

**File:** `resources/views/user/login.php` hoặc `registration.php`

```php
<a href="index.php?route=google-login">Đăng nhập bằng Google</a>
```

**Router:** `public/index.php` → Route `google-login` → Load `google-login.php`

---

### **BƯỚC 2: google-login.php - Chuyển hướng đến Google**

**File:** `public/google-login.php`

```php
// 1. Load config từ config.php
$config = require __DIR__ . '/../config/config.php';
$googleConfig = $config['google'];

// 2. Lấy Client ID và Redirect URI
$clientID = $googleConfig['client_id'];
$redirectURI = $googleConfig['redirect_uri'];

// 3. Tạo URL xác thực Google OAuth
$loginURL = "https://accounts.google.com/o/oauth2/auth?response_type=code"
    . "&client_id=" . $clientID
    . "&redirect_uri=" . urlencode($redirectURI)
    . "&scope=email%20profile";  // Yêu cầu quyền truy cập email và profile

// 4. Chuyển hướng người dùng đến Google
header("Location: $loginURL");
exit;
```

**Kết quả:** Người dùng được chuyển đến trang đăng nhập của Google

---

### **BƯỚC 3: Google xác thực và trả về code**

Người dùng:
1. Đăng nhập vào tài khoản Google
2. Cho phép ứng dụng truy cập thông tin (email, profile)
3. Google chuyển hướng về `redirect_uri` kèm theo `code`

**URL trả về:**
```
http://localhost/flyhighenglish/public/google-callback.php?code=ABC123XYZ...
```

---

### **BƯỚC 4: google-callback.php - Xử lý callback**

**File:** `public/google-callback.php`

#### **4.1. Nhận code từ Google**

```php
// Kiểm tra có code không
if (!isset($_GET['code'])) {
    // Không có code → Lỗi → Quay về trang login
    $_SESSION['error_message'] = "Không nhận được mã xác thực (code).";
    header("Location: index.php?route=login");
    exit;
}
```

#### **4.2. Đổi code lấy Access Token**

```php
// URL API của Google để đổi code lấy token
$tokenURL = "https://oauth2.googleapis.com/token";

$data = [
    'code' => $_GET['code'],              // Code nhận được từ Google
    'client_id' => $clientID,             // Client ID của bạn
    'client_secret' => $clientSecret,     // Client Secret (bảo mật)
    'redirect_uri' => $redirectURI,        // Phải khớp với redirect_uri đã gửi
    'grant_type' => 'authorization_code'  // Loại grant
];

// Gửi request POST đến Google
$curl = curl_init($tokenURL);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
$tokenInfo = json_decode($response, true);
// $tokenInfo = ['access_token' => '...', 'expires_in' => 3600, ...]
```

**Kết quả:** Nhận được `access_token` từ Google

#### **4.3. Lấy thông tin người dùng từ Google**

```php
// Sử dụng access_token để lấy thông tin user
$userInfoURL = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $tokenInfo['access_token'];

$userData = json_decode(file_get_contents($userInfoURL), true);
// $userData = [
//     'id' => '123456789',
//     'email' => 'user@gmail.com',
//     'name' => 'Nguyễn Văn A',
//     'picture' => 'https://...',
//     'verified_email' => true
// ]
```

---

### **BƯỚC 5: Kiểm tra và xử lý đăng nhập/đăng ký**

**File:** `public/google-callback.php` (tiếp tục)

```php
// Tạo instance User model
$userModel = new User();

// Kiểm tra email đã tồn tại trong database chưa
$existingUser = $userModel->findUserByEmail($userData['email']);
```

#### **Trường hợp 1: User ĐÃ TỒN TẠI → Đăng nhập**

```php
if ($existingUser) {
    // Xóa password khỏi session (bảo mật)
    unset($existingUser['password']);
    
    // Lưu thông tin user vào session
    $_SESSION['user'] = $existingUser;
    $_SESSION['success_message'] = 'Đăng nhập thành công với Google.';
    
    // Chuyển về trang chủ
    header("Location: index.php?route=home");
    exit;
}
```

**Kết quả:** User đã đăng nhập, thông tin lưu trong `$_SESSION['user']`

#### **Trường hợp 2: User CHƯA TỒN TẠI → Đăng ký mới**

```php
else {
    // Chuẩn bị dữ liệu đăng ký
    $registerData = [
        'user_name' => $userData['name'] ?? 'Người dùng Google',
        'email' => $userData['email'],
        'phone' => '',                    // Google không cung cấp số điện thoại
        'password' => '',                  // Không cần password cho Google user
        'avatar' => $userData['picture'] ?? null,  // Avatar từ Google
        'role' => 'student'                // Mặc định là student
    ];

    // Gọi method đăng ký
    $result = $userModel->registerWithGoogle($registerData);
    
    if ($result === true) {
        // Đăng ký thành công → Lấy lại user vừa tạo
        $newUser = $userModel->findUserByEmail($userData['email']);
        
        if ($newUser) {
            unset($newUser['password']);
            $_SESSION['user'] = $newUser;
            $_SESSION['success_message'] = 'Đăng ký thành công với Google!';
        }
    }
}
```

---

### **BƯỚC 6: registerWithGoogle() - Lưu vào Database**

**File:** `app/models/User.php` - Method `registerWithGoogle()`

```php
public function registerWithGoogle($data) {
    try {
        // 1. Kiểm tra email đã tồn tại chưa (double check)
        if ($this->findUserByEmail($data['email'])) {
            return 'Email này đã được sử dụng.';
        }

        // 2. Tạo password ngẫu nhiên (vì database yêu cầu NOT NULL)
        // User đăng nhập bằng Google nên không cần password này
        $randomPassword = bin2hex(random_bytes(32));  // Tạo chuỗi ngẫu nhiên 64 ký tự
        $hashed = password_hash($randomPassword, PASSWORD_DEFAULT);  // Hash password

        // 3. SQL INSERT vào bảng user
        $sql = "INSERT INTO `user` (user_name, email, phone, password, role, avatar)
                VALUES (:user_name, :email, :phone, :password, :role, :avatar)";

        $params = [
            ':user_name' => $data['user_name'],      // Tên từ Google
            ':email'     => $data['email'],          // Email từ Google
            ':phone'     => $data['phone'] ?: null,   // NULL (Google không cung cấp)
            ':password'  => $hashed,                  // Password ngẫu nhiên đã hash
            ':role'      => $data['role'] ?? 'student', // Mặc định 'student'
            ':avatar'    => $data['avatar'] ?? null   // URL avatar từ Google
        ];

        // 4. Thực thi query
        $ok = $this->db->execute($sql, $params);
        
        if ($ok) {
            return true;  // Thành công
        }
        return 'Không thể ghi vào cơ sở dữ liệu.';
        
    } catch (PDOException $e) {
        error_log('User::registerWithGoogle PDOException: ' . $e->getMessage());
        return $e->getMessage();
    }
}
```

#### **Chi tiết SQL INSERT:**

**Bảng `user` trong database sẽ có dữ liệu:**
```sql
INSERT INTO `user` (user_name, email, phone, password, role, avatar)
VALUES (
    'Nguyễn Văn A',                    -- user_name từ Google
    'user@gmail.com',                  -- email từ Google
    NULL,                              -- phone (NULL vì Google không cung cấp)
    '$2y$10$abc123...',                -- password đã hash (ngẫu nhiên, user không dùng)
    'student',                         -- role mặc định
    'https://lh3.googleusercontent.com/...'  -- avatar URL từ Google
);
```

**Lưu ý quan trọng:**
- Password được tạo ngẫu nhiên và hash, nhưng user không bao giờ dùng password này
- User chỉ đăng nhập bằng Google OAuth
- Email là unique identifier (không trùng lặp)

---

## 📊 Sơ đồ luồng dữ liệu

```
┌─────────────────┐
│  Người dùng     │
│  Click "Login   │
│  with Google"   │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ google-login.php│
│ Tạo OAuth URL   │
│ → Google        │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│   Google OAuth  │
│   Xác thực user │
│   → Trả code    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│google-callback.php│
│ 1. Nhận code     │
│ 2. Đổi code →    │
│    access_token  │
│ 3. Lấy user info │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  User Model      │
│ findUserByEmail()│
│ Kiểm tra DB      │
└────────┬────────┘
         │
    ┌────┴────┐
    │         │
    ▼         ▼
┌────────┐ ┌──────────────┐
│ Đã có  │ │ Chưa có      │
│        │ │              │
│ Login  │ │ registerWith │
│ Session│ │ Google()     │
│        │ │ → INSERT DB  │
└────────┘ └──────┬───────┘
                  │
                  ▼
            ┌──────────┐
            │ Database │
            │ INSERT   │
            │ user     │
            └──────────┘
```

---

## 🔐 Bảo mật

1. **Client Secret:** Chỉ lưu ở server, không bao giờ expose ra client
2. **Password:** Tạo ngẫu nhiên, hash bằng `password_hash()`
3. **Session:** Lưu user info trong `$_SESSION`, không lưu password
4. **HTTPS:** Nên dùng HTTPS trong production
5. **Redirect URI:** Phải khớp chính xác với Google Console

---

## 📝 Tóm tắt

1. **User click** → `google-login.php` → Chuyển đến Google
2. **Google xác thực** → Trả về `code` → `google-callback.php`
3. **Đổi code** → Lấy `access_token` → Lấy thông tin user
4. **Kiểm tra DB:**
   - **Có email** → Đăng nhập (lưu session)
   - **Chưa có** → `registerWithGoogle()` → INSERT vào DB → Đăng nhập

**Kết quả:** User được lưu vào database và đăng nhập thành công!

