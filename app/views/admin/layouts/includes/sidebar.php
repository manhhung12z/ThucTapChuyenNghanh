<aside id="adminSidebar" class="vh-100 d-flex flex-column text-white shadow sidebar-gradient" style="width: 260px; min-width: 260px; z-index: 1050;">
    
    <div class="p-4 d-flex align-items-center justify-content-between border-bottom border-light border-opacity-10">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                <span class="fw-bold fs-5" style="color: #9333ea;">P</span>
            </div>
            <span class="fs-5 fw-bold" style="letter-spacing: 0.05em;">PRIVIA</span>
        </div>
        
        <button onclick="toggleSidebar()" class="btn btn-sm text-white-50 hover-text-white d-md-none border-0" aria-label="Close Sidebar">
            ✕
        </button>
    </div>
    
    <nav class="flex-grow-1 px-3 mt-4 overflow-y-auto custom-scrollbar d-flex flex-column gap-1">
        <?php
        $current_url = $_SERVER['REQUEST_URI'];
        function is_active($path, $current_url) {
            // Nếu là trang hiện tại -> nền trắng trong suốt, chữ đậm
            // Nếu không -> chữ hơi mờ, hiệu ứng hover
            return strpos($current_url, $path) !== false 
                ? 'bg-white bg-opacity-25 text-white fw-medium ' 
                : 'text-white-80 hover-bg-light-10 text-decoration-none';
        }
        ?>
        
        <a href="<?=url('admin/DashboardController/index')?>" class="d-flex align-items-center p-3 rounded-3 transition-all <?= is_active('DashboardController', $current_url) ?>">
            <span class="fs-5">📊</span> <span class="ms-3 fs-6">Tổng quan</span>
        </a>
        <a href="<?=url('admin/ProductController/index')?>" class="d-flex align-items-center p-3 rounded-3 transition-all <?= is_active('ProductController', $current_url) ?>">
            <span class="fs-5">💄</span> <span class="ms-3 fs-6">Dòng sản phẩm</span>
        </a>
        <a href="<?=url('admin/CategoryController/index')?>" class="d-flex align-items-center p-3 rounded-3 transition-all <?= is_active('CategoryController', $current_url) ?>">
            <span class="fs-5">📦</span> <span class="ms-3 fs-6">Danh mục</span>
        </a>
        <a href="<?=url('admin/OrderController/index')?>" class="d-flex align-items-center p-3 rounded-3 transition-all <?= is_active('OrderController', $current_url) ?>">
            <span class="fs-5">🛍️</span> <span class="ms-3 fs-6">Đơn hàng</span>
        </a>
        <a href="<?=url('admin/DiscountController/index')?>" class="d-flex align-items-center p-3 rounded-3 transition-all <?= is_active('DiscountController', $current_url) ?>">
            <span class="fs-5">⚡</span> <span class="ms-3 fs-6">Flash Sale</span>
        </a>
        <a href="<?=url('admin/ReviewController/index')?>" class="d-flex align-items-center p-3 rounded-3 transition-all <?= is_active('ReviewController', $current_url) ?>">
            <span class="fs-5">👍</span> <span class="ms-3 fs-6">Đánh giá</span>
        </a>
        <a href="<?=url('admin/AccountController/index')?>" class="d-flex align-items-center p-3 rounded-3 transition-all <?= is_active('AccountController', $current_url) ?>">
            <span class="fs-5">👤</span> <span class="ms-3 fs-6">Người dùng</span>
        </a>
        <a href="<?=url('admin/NewsController/index')?>" class="d-flex align-items-center p-3 rounded-3 transition-all <?= is_active('NewsController', $current_url) ?>">
            <span class="fs-5">📝</span> <span class="ms-3 fs-6">Blog & Tin tức</span>
        </a>
    </nav>
    
    <div class="p-4 border-top border-light border-opacity-10 text-center" style="font-size: 0.75rem; color: rgba(255,255,255,0.6);">
        Copyright © Privia 2026
    </div>
</aside>
