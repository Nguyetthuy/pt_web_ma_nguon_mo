<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-file-alt"></i> Tài liệu học tiếng Anh</h2>
        <p class="text-muted">Tải xuống các tài liệu học tiếng Anh miễn phí</p>
    </div>
    <div class="col-md-4 text-end">
        <?php if (isset($_SESSION['user'])): ?>
            <a href="<?php echo $base; ?>/index.php?route=documents_create" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload tài liệu
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($documents)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Chưa có tài liệu nào. 
        <?php if (isset($_SESSION['user'])): ?>
            <a href="<?php echo $base; ?>/index.php?route=documents_create">Upload tài liệu đầu tiên</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($documents as $doc): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                <?php
                                $iconClass = 'fa-file';
                                $iconColor = 'text-secondary';
                                switch ($doc['file_type']) {
                                    case 'pdf':
                                        $iconClass = 'fa-file-pdf';
                                        $iconColor = 'text-danger';
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $iconClass = 'fa-file-word';
                                        $iconColor = 'text-primary';
                                        break;
                                    case 'ppt':
                                    case 'pptx':
                                        $iconClass = 'fa-file-powerpoint';
                                        $iconColor = 'text-warning';
                                        break;
                                    case 'xls':
                                    case 'xlsx':
                                        $iconClass = 'fa-file-excel';
                                        $iconColor = 'text-success';
                                        break;
                                    case 'zip':
                                    case 'rar':
                                        $iconClass = 'fa-file-archive';
                                        $iconColor = 'text-info';
                                        break;
                                }
                                ?>
                                <i class="fas <?php echo $iconClass; ?> fa-3x <?php echo $iconColor; ?>"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($doc['title']); ?></h5>
                                <small class="text-muted">
                                    <?php echo strtoupper($doc['file_type']); ?> • 
                                    <?php echo number_format($doc['file_size'] / 1024, 0); ?> KB
                                </small>
                            </div>
                        </div>
                        
                        <?php if (!empty($doc['description'])): ?>
                            <p class="card-text text-muted small">
                                <?php echo htmlspecialchars(substr($doc['description'], 0, 100)); ?>
                                <?php echo strlen($doc['description']) > 100 ? '...' : ''; ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-download"></i> <?php echo $doc['downloads'] ?? 0; ?> lượt tải
                            </small>
                            <small class="text-muted">
                                <?php echo date('d/m/Y', strtotime($doc['created_at'])); ?>
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex gap-2">
                            <a href="<?php echo $base; ?>/index.php?route=documents_download&id=<?php echo $doc['id']; ?>" 
                               class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-download"></i> Tải xuống
                            </a>
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['user_id'] == $doc['uploaded_by']): ?>
                                <a href="<?php echo $base; ?>/index.php?route=documents_delete&id=<?php echo $doc['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Bạn có chắc muốn xóa tài liệu này?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
