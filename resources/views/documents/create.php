<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-upload"></i> Upload tài liệu học tiếng Anh</h4>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo $base; ?>/index.php?route=documents_store" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề tài liệu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required 
                               placeholder="Ví dụ: Ngữ pháp tiếng Anh cơ bản">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="Mô tả ngắn về nội dung tài liệu..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="document" class="form-label">Chọn file <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="document" name="document" required>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> 
                            Hỗ trợ: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR. Tối đa 10MB.
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Lưu ý:</strong> Vui lòng chỉ upload tài liệu có bản quyền hoặc tài liệu miễn phí. 
                        Tài liệu vi phạm bản quyền sẽ bị xóa.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload tài liệu
                        </button>
                        <a href="<?php echo $base; ?>/index.php?route=documents" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-question-circle"></i> Hướng dẫn upload</h5>
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li>Nhập tiêu đề rõ ràng, dễ hiểu cho tài liệu</li>
                    <li>Thêm mô tả chi tiết về nội dung tài liệu (Tùy chọn)</li>
                    <li>Chọn file từ máy tính của bạn</li>
                    <li>Nhấn "Upload tài liệu" để hoàn tất</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
