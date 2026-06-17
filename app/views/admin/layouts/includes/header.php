<header class="d-flex justify-content-between align-items-center bg-white px-4 py-4 border-bottom w-100" style="border-color: #f3f4f6 !important;">
    
    <div class="d-flex align-items-center gap-2 gap-sm-3 min-vw-0">
        <button onclick="toggleSidebar()" class="btn btn-light d-md-none flex-shrink-0 border-0" aria-label="Open Menu">
            <i class="bi bi-list fs-4"></i>
        </button>

            <?php defineblock('Atitle') ?>
    </div>

    <div class="d-flex align-items-center gap-3 gap-sm-4 flex-shrink-0">
        <a href="<?= url('Admin/DashboardController/index') ?>" class="text-decoration-none text-dark fw-medium">
        <div class="d-flex align-items-center gap-2 border-start ps-3 ps-sm-4" style="border-color: #f3f4f6 !important;">
            <?php if (isset($_SESSION['admin']) || isset($_SESSION['staff'])): ?>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle text-dark fw-medium" data-bs-toggle="dropdown">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="d-none d-sm-block">
                            <?php if (isset($_SESSION['admin'])): ?>
                                <?= $_SESSION['admin']['TenNguoiDung'] ?>
                            <?php elseif (isset($_SESSION['staff'])): ?>
                                <?= $_SESSION['staff']['TenNguoiDung'] ?>
                            <?php endif; ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 rounded-3">
                        <li><a class="dropdown-item text-danger" href="<?= _WEB_ROOT ?>/AuthController/logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                        </a></li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="d-flex gap-2">
                    <a href="<?= url('AuthController/login') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Đăng nhập</a>
                </div>
            <?php endif; ?>
    </div>

</header>