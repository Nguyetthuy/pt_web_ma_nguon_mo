<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="py-4">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
      <p class="text-muted mb-1">Xin chào, <?php echo htmlspecialchars($admin['user_name'] ?? 'Admin'); ?></p>
      <h2 class="fw-bold mb-1">Quản lý người dùng</h2>
      <p class="text-muted mb-0">Chọn một tài khoản để cập nhật, khóa hoặc thêm tài khoản mới.</p>
    </div>
    <div class="mt-3 mt-md-0">
      <a class="btn btn-outline-secondary me-2" href="<?php echo $base; ?>/index.php?route=admin">
        <i class="fa fa-arrow-left me-2"></i>Quay về dashboard
      </a>
      <button class="btn btn-success me-2" type="button" data-action="create">
        <i class="fa fa-user-plus me-2"></i>Thêm
      </button>
      <button class="btn btn-warning me-2" type="button" data-action="edit" disabled>
        <i class="fa fa-pen me-2"></i>Sửa
      </button>
      <button class="btn btn-danger" type="button" data-action="delete" disabled>
        <i class="fa fa-trash me-2"></i>Xóa
      </button>
    </div>
  </div>

  <?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:48px;"></th>
              <th>#</th>
              <th>Người dùng</th>
              <th>Email</th>
              <th>Điện thoại</th>
              <th>Quyền</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($users)): ?>
              <?php foreach ($users as $user): ?>
                <tr>
                  <td>
                    <input
                      type="radio"
                      class="form-check-input user-radio"
                      name="selected_user"
                      value="<?php echo (int)$user['user_id']; ?>"
                      data-name="<?php echo htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8'); ?>"
                      data-email="<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>"
                      data-phone="<?php echo htmlspecialchars($user['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                      data-role="<?php echo htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8'); ?>"
                    >
                  </td>
                  <td><?php echo (int)$user['user_id']; ?></td>
                  <td>
                    <div class="d-flex align-items-center">
                      <?php if (!empty($user['avatar'])): ?>
                        <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="avatar" class="rounded-circle me-3" style="width:40px;height:40px;object-fit:cover;">
                      <?php else: ?>
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                          <i class="fa fa-user"></i>
                        </div>
                      <?php endif; ?>
                      <div>
                        <div class="fw-semibold"><?php echo htmlspecialchars($user['user_name']); ?></div>
                        <small class="text-muted">ID: <?php echo (int)$user['user_id']; ?></small>
                      </div>
                    </div>
                  </td>
                  <td><?php echo htmlspecialchars($user['email']); ?></td>
                  <td><?php echo htmlspecialchars($user['phone'] ?? '—'); ?></td>
                  <td>
                    <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'secondary'; ?>">
                      <?php echo strtoupper(htmlspecialchars($user['role'])); ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-4">Chưa có người dùng nào trong hệ thống.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal thêm / sửa -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="userModalForm" method="post" action="<?php echo $base; ?>/index.php?route=admin&section=users">
      <div class="modal-header">
        <h5 class="modal-title">Quản lý người dùng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" id="modal-action" value="">
        <input type="hidden" name="user_id" id="modal-user-id" value="">

        <div class="mb-3">
          <label for="modal-user-name" class="form-label">Tên người dùng</label>
          <input type="text" class="form-control" id="modal-user-name" name="user_name" required>
        </div>

        <div class="mb-3">
          <label for="modal-email" class="form-label">Email</label>
          <input type="email" class="form-control" id="modal-email" name="email" required>
        </div>

        <div class="mb-3">
          <label for="modal-phone" class="form-label">Số điện thoại</label>
          <input type="text" class="form-control" id="modal-phone" name="phone" placeholder="Tùy chọn">
        </div>

        <div class="mb-3">
          <label for="modal-role" class="form-label">Quyền</label>
          <select class="form-select" id="modal-role" name="role">
            <option value="student">Student</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <div class="mb-0">
          <label for="modal-password" class="form-label">Mật khẩu</label>
          <input type="password" class="form-control" id="modal-password" name="password">
          <div class="form-text" id="password-help-text">Bắt buộc khi tạo mới người dùng.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="post" action="<?php echo $base; ?>/index.php?route=admin&section=users">
      <div class="modal-header">
        <h5 class="modal-title text-danger">Xóa người dùng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="user_id" id="delete-user-id">
        <p class="mb-0">Bạn có chắc chắn muốn xóa tài khoản này? Hành động không thể hoàn tác.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-danger">Xóa</button>
      </div>
    </form>
  </div>
</div>

<script>
window.addEventListener('load', function () {
  (function () {
  const radios = document.querySelectorAll('.user-radio');
  const editBtn = document.querySelector('[data-action="edit"]');
  const deleteBtn = document.querySelector('[data-action="delete"]');
  const createBtn = document.querySelector('[data-action="create"]');
  const userModalEl = document.getElementById('userModal');
  const userModal = new bootstrap.Modal(userModalEl);
  const userModalForm = document.getElementById('userModalForm');
  const modalTitle = userModalEl.querySelector('.modal-title');
  const actionInput = document.getElementById('modal-action');
  const modalUserId = document.getElementById('modal-user-id');
  const nameInput = document.getElementById('modal-user-name');
  const emailInput = document.getElementById('modal-email');
  const phoneInput = document.getElementById('modal-phone');
  const roleInput = document.getElementById('modal-role');
  const passwordInput = document.getElementById('modal-password');
  const passwordHelp = document.getElementById('password-help-text');
  const deleteModalEl = document.getElementById('deleteModal');
  const deleteModal = new bootstrap.Modal(deleteModalEl);
  const deleteUserId = document.getElementById('delete-user-id');

  const getSelectedRadio = () => document.querySelector('.user-radio:checked');

  radios.forEach(radio => {
    radio.addEventListener('change', () => {
      document.querySelectorAll('table tbody tr').forEach(row => row.classList.remove('table-active'));
      radio.closest('tr').classList.add('table-active');
      editBtn.disabled = false;
      deleteBtn.disabled = false;
    });

    radio.closest('tr').addEventListener('click', (e) => {
      if (e.target.matches('input[type="radio"], button')) return;
      radio.checked = true;
      radio.dispatchEvent(new Event('change'));
    });
  });

  createBtn.addEventListener('click', () => {
    userModalForm.reset();
    actionInput.value = 'create';
    modalUserId.value = '';
    modalTitle.textContent = 'Thêm người dùng';
    passwordInput.required = true;
    passwordHelp.textContent = 'Bắt buộc khi tạo mới người dùng.';
    userModal.show();
  });

  editBtn.addEventListener('click', () => {
    const selected = getSelectedRadio();
    if (!selected) return;
    actionInput.value = 'update';
    modalUserId.value = selected.value;
    modalTitle.textContent = 'Sửa thông tin người dùng';
    nameInput.value = selected.dataset.name || '';
    emailInput.value = selected.dataset.email || '';
    phoneInput.value = selected.dataset.phone || '';
    roleInput.value = selected.dataset.role || 'student';
    passwordInput.value = '';
    passwordInput.required = false;
    passwordHelp.textContent = 'Để trống nếu không muốn đổi mật khẩu.';
    userModal.show();
  });

  deleteBtn.addEventListener('click', () => {
    const selected = getSelectedRadio();
    if (!selected) return;
    deleteUserId.value = selected.value;
    deleteModal.show();
  });
  })();
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>

