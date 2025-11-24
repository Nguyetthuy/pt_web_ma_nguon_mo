<?php
/**
 * Script debug để kiểm tra Redirect URI chính xác
 * Truy cập: http://localhost/flyhighenglish/public/google-oauth-debug.php
 */

// Tính toán redirect URI tự động
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = rtrim($scriptPath, '/\\');

// Tùy chọn 1: Sử dụng route
$redirectURI1 = $protocol . '://' . $host . $basePath . '/index.php?route=google-callback';

// Tùy chọn 2: Sử dụng file trực tiếp
$redirectURI2 = $protocol . '://' . $host . $basePath . '/google-callback.php';

// Lấy config hiện tại
$config = require __DIR__ . '/../config/config.php';
$currentRedirectURI = $config['google']['redirect_uri'] ?? 'Chưa được cấu hình';

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google OAuth Debug - Redirect URI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .code-block {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        .match { color: #28a745; font-weight: bold; }
        .mismatch { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">🔍 Google OAuth - Kiểm tra Redirect URI</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Hướng dẫn:</strong> Copy một trong các URI bên dưới và đăng ký trong Google Cloud Console.
                        </div>

                        <h5 class="mt-4">📍 Redirect URI được tính toán tự động:</h5>
                        
                        <div class="mb-3">
                            <label class="form-label"><strong>Tùy chọn 1: Sử dụng Route (Khuyến nghị)</strong></label>
                            <div class="code-block" id="uri1"><?php echo htmlspecialchars($redirectURI1); ?></div>
                            <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('uri1')">📋 Copy</button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Tùy chọn 2: Sử dụng File trực tiếp</strong></label>
                            <div class="code-block" id="uri2"><?php echo htmlspecialchars($redirectURI2); ?></div>
                            <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('uri2')">📋 Copy</button>
                        </div>

                        <hr>

                        <h5 class="mt-4">⚙️ Redirect URI hiện tại trong config.php:</h5>
                        <div class="code-block">
                            <?php echo htmlspecialchars($currentRedirectURI); ?>
                        </div>

                        <?php 
                        $isMatch1 = ($currentRedirectURI === $redirectURI1);
                        $isMatch2 = ($currentRedirectURI === $redirectURI2);
                        $isMatched = $isMatch1 || $isMatch2;
                        ?>

                        <div class="alert <?php echo $isMatched ? 'alert-success' : 'alert-warning'; ?> mt-3">
                            <?php if ($isMatched): ?>
                                <strong>✅ Khớp!</strong> Redirect URI trong config.php khớp với URI được tính toán.
                            <?php else: ?>
                                <strong>⚠️ Không khớp!</strong> Redirect URI trong config.php không khớp với URI được tính toán.
                                <br><br>
                                <strong>Hãy cập nhật config.php:</strong>
                                <div class="code-block mt-2">
                                    'redirect_uri' => '<?php echo htmlspecialchars($redirectURI1); ?>'
                                </div>
                            <?php endif; ?>
                        </div>

                        <hr>

                        <h5 class="mt-4">📝 Các bước tiếp theo:</h5>
                        <ol>
                            <li>Copy một trong các URI ở trên (khuyến nghị: Tùy chọn 1)</li>
                            <li>Vào <a href="https://console.cloud.google.com/apis/credentials" target="_blank">Google Cloud Console</a></li>
                            <li>Chọn project → APIs & Services → Credentials</li>
                            <li>Click vào OAuth 2.0 Client ID của bạn</li>
                            <li>Trong phần <strong>"Authorized redirect URIs"</strong>, thêm URI đã copy</li>
                            <li>Nếu config.php chưa khớp, cập nhật file <code>config/config.php</code></li>
                            <li>Lưu và thử đăng nhập lại bằng Google</li>
                        </ol>

                        <div class="mt-4">
                            <a href="index.php?route=login" class="btn btn-primary">← Quay lại trang đăng nhập</a>
                            <button onclick="location.reload()" class="btn btn-secondary">🔄 Làm mới</button>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h6>ℹ️ Thông tin hệ thống:</h6>
                        <ul class="mb-0">
                            <li><strong>Protocol:</strong> <?php echo $protocol; ?></li>
                            <li><strong>Host:</strong> <?php echo $host; ?></li>
                            <li><strong>Script Path:</strong> <?php echo $scriptPath; ?></li>
                            <li><strong>Base Path:</strong> <?php echo $basePath; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent.trim();
            
            navigator.clipboard.writeText(text).then(function() {
                alert('✅ Đã copy vào clipboard!\n\n' + text);
            }, function(err) {
                // Fallback cho trình duyệt cũ
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('✅ Đã copy vào clipboard!\n\n' + text);
            });
        }
    </script>
</body>
</html>

