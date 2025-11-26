# 🔒 Hướng dẫn sửa lỗi GitHub Secret Detection

## ❌ Vấn đề
GitHub đã phát hiện Google Client Secret trong file `config/config.php` và từ chối push code.

## ✅ Giải pháp

### Bước 1: Đã thêm config.php vào .gitignore
File `config/config.php` đã được thêm vào `.gitignore` để không commit secret nữa.

### Bước 2: Xóa file khỏi Git tracking (nhưng giữ file local)

Chạy các lệnh sau trong PowerShell:

```powershell
# Xóa file khỏi git tracking (nhưng giữ file trên máy)
git rm --cached config/config.php

# Commit thay đổi
git add .gitignore
git commit -m "Remove config.php from git tracking (contains secrets)"
```

### Bước 3: Xử lý secret đã bị commit

**Có 2 lựa chọn:**

#### **Lựa chọn A: Revoke Secret cũ và tạo mới (KHUYẾN NGHỊ)**

1. Vào Google Cloud Console: https://console.cloud.google.com/
2. APIs & Services → Credentials
3. Click vào OAuth 2.0 Client ID của bạn
4. **Revoke** (thu hồi) Client Secret hiện tại
5. Tạo **Client Secret mới**
6. Cập nhật `config/config.php` với secret mới
7. Push code lên GitHub (secret cũ đã bị revoke nên an toàn hơn)

#### **Lựa chọn B: Allow secret trên GitHub (KHÔNG KHUYẾN NGHỊ)**

1. Follow URL GitHub cung cấp:
   ```
   https://github.com/Nguyetthuy/pt_web_ma_nguon_mo/security/secret-scanning/unblock-secret/35ucaXATVsZ8OSxxtbEWEzdEIYv
   ```
2. Click "Allow secret" (KHÔNG AN TOÀN - secret sẽ bị expose)

### Bước 4: Push code

Sau khi xử lý secret, push lại:

```powershell
git push origin main
```

## 📝 Lưu ý quan trọng

1. **KHÔNG BAO GIỜ** commit secret vào Git
2. Luôn dùng `.gitignore` để bỏ qua file chứa secret
3. Dùng `config.example.php` làm template
4. Trong production, nên dùng environment variables

## 🔄 Quy trình cho người khác clone project

1. Clone project
2. Copy `config/config.example.php` thành `config/config.php`
3. Điền thông tin thực tế vào `config/config.php`
4. File `config/config.php` sẽ không được commit (đã có trong .gitignore)

