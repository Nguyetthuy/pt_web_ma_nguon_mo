# Hướng dẫn sửa lỗi Google OAuth - redirect_uri_mismatch

## Lỗi: redirect_uri_mismatch

Lỗi này xảy ra khi **Redirect URI** trong code không khớp chính xác với **Redirect URI** đã đăng ký trong Google Cloud Console.

## 🔍 Cách nhanh nhất - Sử dụng trang Debug:

1. **Truy cập trang debug:**
   ```
   http://localhost/flyhighenglish/public/google-oauth-debug.php
   ```
   (Thay `flyhighenglish` bằng tên thư mục thực tế của bạn)

2. **Trang debug sẽ hiển thị:**
   - Redirect URI chính xác được tính toán tự động
   - Redirect URI hiện tại trong config.php
   - So sánh xem có khớp không
   - Nút copy để copy URI dễ dàng

3. **Copy URI từ trang debug** và làm theo các bước bên dưới

## Cách sửa thủ công:

### Bước 1: Kiểm tra Redirect URI trong code

Mở file `config/config.php` và kiểm tra giá trị `redirect_uri`:

```php
'redirect_uri' => 'http://localhost/flyhighenglish/public/index.php?route=google-callback'
```

**LƯU Ý QUAN TRỌNG:**
- URI phải khớp **CHÍNH XÁC** (bao gồm cả http/https, localhost, tên thư mục, đường dẫn)
- Không có dấu `/` thừa ở cuối
- Phân biệt chữ hoa/chữ thường

### Bước 2: Đăng ký Redirect URI trong Google Cloud Console

1. Truy cập: https://console.cloud.google.com/
2. Chọn project của bạn
3. Vào **APIs & Services** > **Credentials**
4. Click vào **OAuth 2.0 Client ID** của bạn
5. Trong phần **Authorized redirect URIs**, thêm URI chính xác như trong `config/config.php`

**Các URI có thể sử dụng:**

**Tùy chọn 1: Sử dụng route (khuyến nghị)**
```
http://localhost/flyhighenglish/public/index.php?route=google-callback
```

**Tùy chọn 2: Sử dụng file trực tiếp**
```
http://localhost/flyhighenglish/public/google-callback.php
```

### Bước 3: Cập nhật config.php

Sau khi đăng ký URI trong Google Console, cập nhật `config/config.php` để khớp với URI đã đăng ký:

```php
'redirect_uri' => 'http://localhost/flyhighenglish/public/index.php?route=google-callback'
// HOẶC
'redirect_uri' => 'http://localhost/flyhighenglish/public/google-callback.php'
```

### Bước 4: Kiểm tra tên thư mục

Đảm bảo tên thư mục trong URI khớp với tên thư mục thực tế:
- `flyhighenglish` (không có chữ "t" sau "high")
- Hoặc `flyhightenglish` (có chữ "t")

Kiểm tra đường dẫn thực tế của bạn và cập nhật cho đúng.

### Bước 5: Test lại

1. Xóa cache trình duyệt (hoặc dùng chế độ ẩn danh)
2. Thử đăng nhập bằng Google lại
3. Nếu vẫn lỗi, kiểm tra lại URI trong Google Console và config.php

## Lưu ý khi deploy lên production:

Khi deploy lên server thực, bạn cần:
1. Thêm domain mới vào **Authorized JavaScript origins** trong Google Console
2. Thêm redirect URI mới với domain thực vào **Authorized redirect URIs**
3. Cập nhật `config.php` với redirect URI mới

Ví dụ:
```
https://yourdomain.com/public/index.php?route=google-callback
```

