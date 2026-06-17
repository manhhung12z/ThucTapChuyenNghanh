<?php require_once(_DIR_ROOT . '/app/views/admin/layouts/index.php') ?>

<?php startblock('Acss', 0) ?>
    <link href="<?= asset('assets/admin/css/styleaccount.css') ?>" rel="stylesheet">
<?php endblock() ?>

<?php startblock('Atitle', 0) ?>
    <h1 class="fs-4 fw-bold text-dark mb-0 text-truncate">Quản lý tài khoản</h1>
<?php endblock() ?>

<?php startblock('Acontent', 0) ?>
    <div class="mb-4"></div>

    <div class="privia-card overflow-hidden">
        
        <div class="px-4 py-4 d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
            <span class="fw-bold text-dark" style="font-size: 0.95rem; color: #1f2937 !important;">Tất cả tài khoản</span>
            <input type="text" 
                   id="searchAccount"
                   placeholder="Tìm kiếm tài khoản..." 
                   class="form-control search-privia w-100 shadow-none" 
                   style="max-width: 100%;">
        </div>

        <div class="table-responsive w-100">
            <table class="table table-borderless table-privia align-middle mb-0" style="min-width: 850px; table-layout: fixed;">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 25%;">TÊN NGƯỜI DÙNG</th>
                        <th style="width: 25%;">EMAIL</th>
                        <th style="width: 20%;">SỐ ĐIỆN THOẠI</th>
                        <th class="text-center" style="width: 15%;">QUYỀN</th>
                        <th class="text-center pe-4" style="width: 15%;">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody id="accountTableBody">
                    <?php 
                    if (!empty($accounts)): 
                        foreach ($accounts as $acc): 
                            $accJson = json_encode([
                                'MaTaiKhoan' => $acc['mataikhoan'] ?? '',
                                'TenNguoiDung' => $acc['tennguoidung'] ?? '',
                                'Email' => $acc['email'] ?? '',
                                'SoDienThoai' => $acc['sodienthoai'] ?? '',
                                'LoaiTaiKhoan' => $acc['loaitaikhoan'] ?? 'user'
                            ], JSON_UNESCAPED_UNICODE);
                            $accJsonHtml = htmlspecialchars($accJson, ENT_QUOTES, 'UTF-8');
                    ?>
                        <tr data-id="<?php echo $acc['mataikhoan']; ?>">
                            <td class="ps-4 fw-semibold text-dark truncate-col" style="color: #111827 !important;">
                                <?php echo $acc['tennguoidung']; ?>
                            </td>
                            
                            <td class="text-secondary truncate-col">
                                <?php echo $acc['email']; ?>
                            </td>
                            
                            <td class="text-muted text-nowrap">
                                <?php echo !empty($acc['sodienthoai']) ? $acc['sodienthoai'] : '<span class="text-light-emphasis text-opacity-50 fst-italic">Chưa có</span>'; ?>
                            </td>
                            
                            <td class="text-center text-nowrap">
                                <?php if (($acc['loaitaikhoan'] ?? 'user') === 'admin'): ?>
                                    <span class="badge bg-danger-subtle text-danger px-2 py-1 border border-danger-subtle" style="font-size: 0.75rem; font-weight: 600; border-radius: 6px;">Admin</span>
                                    
                                <?php elseif (($acc['loaitaikhoan'] ?? 'user') === 'user'): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 border border-warning-subtle" style="font-size: 0.75rem; font-weight: 600; border-radius: 6px;">User</span>
                                    
                                <?php elseif (($acc['loaitaikhoan'] ?? 'user') === 'staff'): ?>
                                    <span class="badge bg-info-subtle text-info-emphasis px-2 py-1 border border-info-subtle" style="font-size: 0.75rem; font-weight: 600; border-radius: 6px;">Staff</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <button onclick="openModal('edit', <?= $accJsonHtml ?>)" class="btn btn-action-privia btn-edit-privia">⚙️ Xử lý</button>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted" style="font-size: 0.9rem;">
                                Chưa có tài khoản nào trong hệ thống Privia.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> 
        
        <?php require_once _DIR_ROOT . '/app/views/admin/layouts/includes/pagination.php'; ?>
    </div>

    <?php require_once _DIR_ROOT . '/app/views/admin/account/modal.php'; ?>
<?php endblock() ?>

<?php startblock('Ajs', 0) ?>
    <script>
        const WEB_ROOT = "<?= _WEB_ROOT ?>";
    </script>
    <script src="<?= asset('assets/admin/js/account.js') ?>"></script>
<?php endblock() ?>