<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/CartController.php');
require_once(_DIR_ROOT . '/core/auth.php');
require_once(_DIR_ROOT . '/app/Models/Web/cart.php');
$currentUser = auth::getUser('user');
$cartModel = new cart();
if ($currentUser) {
    $maGioHang = $cartModel->getOrCreateCart($currentUser['MaTaiKhoan']);
    $cartCount = $cartModel->countItems($maGioHang);
} else {
    $cartCount = 0;
    if (!empty($_SESSION['GuestCart'])) {
        foreach ($_SESSION['GuestCart'] as $item) {
            $cartCount += $item['SoLuongMua'] ?? 0;
        }
    }
}
?>
<header class="header">
    <div class="header_one">
        <div class="container" style="padding: 15px;">
            <div class="navbar d-flex align-items-center justify-content-between">
                <div class="col-auto d-flex align-items-center">
                    <img src="<?php echo asset('assets/web/img/logo.png') ?> ?>"
                        style="height: 50px; width: auto; margin-right: 5%;">
                    <a class="navbar-brand" href="<?php echo url('HomepageController/index') ?>">Privia</a>
                </div>
                <!--ép form chiếm 100% không gian của cột col-->
                <div class="col d-none d-sm-block d-md-flex justify-content-center px-4">
                    <form action="<?php echo url('CategoryController/index') ?>" method="GET" class="search-form w-100" style="max-width: 600px;">
                        <div class="input-group">
                            <input type="text" class="form-control rounded-start-pill border-end-0"
                                placeholder="Tìm kiếm" style="border-color: #ced4da; box-shadow: none;" name="keyword">

                            <button type="submit" class="btn bg-white rounded-end-pill border-start-0"
                                style="border-color: #ced4da; box-shadow: none;"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-auto d-flex align-items-center gap-4">
                    <div class="d-none d-md-block align-items-center">
                        <?php if ($currentUser) { ?>
                            <div class="dropdown user-dropdown">
                                <i class="fa-regular fa-user"></i>
                                <a href="#" class="dropdown-toggle"
                                    style="text-decoration: none; color:inherit" data-bs-toggle="dropdown"><span><?php echo htmlspecialchars($currentUser['TenNguoiDung']); ?></span></a>
                                <ul class="dropdown-menu dropdown-menu-end user-menu">

                                    <li>
                                        <a class="dropdown-item"
                                            href="<?php echo url('AuthController/profile') ?>"><i class="fa-regular fa-user me-2"></i>Tài khoản của tôi</a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item"
                                            href="<?php echo url('OrderController/history') ?>"><i class="fa-solid fa-box me-2"></i>Đơn Hàng</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo url('AuthController/forgot') ?>"><i class="fa-solid fa-lock me-2"></i>Đổi mật khẩu
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li>
                                        <a class="dropdown-item text-danger"
                                            href="<?php echo url('AuthController/logout') ?>">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i>
                                            Đăng xuất
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        <?php } else { ?>
                            <i class="fa-regular fa-user"></i>
                            <a href="<?php echo url('AuthController/login') ?>"
                                style="text-decoration: none; color:inherit">Đăng Nhập</a>
                            <span>/</span>
                            <a href="<?php echo url('AuthController/register') ?>"
                                style="text-decoration: none; color:inherit">Đăng Ký</a>
                        <?php } ?>
                    </div>
                    <div class="cart-icon">
                        <a href="<?php echo url('CartController/index') ?>" style="text-decoration: none; color:inherit; position:relative;">
                            <i class="fa-solid fa-cart-arrow-down"></i>
                            <span class="cart-count"><?php echo $cartCount; ?></span>
                        </a>
                    </div>
                    <!--navbar-toggler thực hiện chức năng căn chỉnh responsive, có viền ,căn chỉnh màn hình-->
                    <!--còn data-bs-* thực hiện chức năng collapse và dropdown ,..-->
                    <!--aria-expanded tạo ra hiệu ứng động giúp người dùng nhỉn rõ chuyển động-->
                    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas"
                        aria-controls="offcanvas" aria-expanded="false" data-bs-target="#offcanvas"><span
                            class="navbar-toggler-icon"></span></button>
                </div>
            </div>

        </div>
    </div>
    <div class="header_two d-none d-lg-flex  align-items-center justify-content-center">
        <div class="nav-item d-flex align-items-center dropdown">
            <i class="fa-solid fa-bars"></i>
            <button type="button" data-bs-toggle="dropdown" class="fw-bold mb-0 dropdown-toggle" style="border: none; background: none;">Danh mục sản phẩm</button>
            <ul class="dropdown-menu" style="border:none;border-radius:16px;padding:12px 8px;min-width:240px;box-shadow:0 10px 30px rgba(0,0,0,0.12);">
                <?php if (!empty($categories)) {
                    foreach ($categories as $category) { ?>
                        <li><a class="dropdown-item" href="<?php echo url('CategoryController/index?MaDanhMuc=' . $category['MaDanhMuc']) ?>" style="padding:12px 16px;border-radius:12px;font-weight:400;transition:0.25s;"><?php echo htmlspecialchars($category['TenDanhMuc']); ?></a></li>
                <?php }
                } ?>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-5" style="margin-left: 80px;">
            <div class="nav-item">
                <a class="nav-link text-dark fw-bold" href="<?php echo url('PolicyController/index') ?>">Chính sách<i class="fas fa-bolt text-warning"></i></a>
            </div>
            <div class="nav-item">
                <a class="nav-link text-dark fw-bold" href="<?php echo url('BrandController/index') ?>">Thương hiệu</a>
            </div>
            <div class="nav-item">
                <a class="nav-link text-dark fw-bold" href="<?php echo url('ShowroomController/index') ?>">Hệ thống cửa hàng</a>
            </div>
            <div class="nav-item">
                <a class="nav-link text-dark fw-bold" href="<?php echo url('NewsController/index') ?>">Tin tức & Blog</a>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas">
        <div class="offcanvas-header">
            <div class="d-flex align-items-center">
                <img src="<?php echo asset('assets/web/img/logo.png') ?>" style="height: 38px; width: auto; margin-right: 10px;">
                <h5 class="offcanvas-title fw-bold" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px;">Privia</h5>
            </div>
            <button type="button" class="btn-close btn-close" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <ul class="navbar-nav">
                <li class="nav-item py-1"><a class="nav-link d-flex align-items-center justify-content-between text-dark" data-bs-toggle="collapse" href="#offcanvasCategoryCollapse" role="button" aria-expanded="false" aria-controls="offcanvasCategoryCollapse">
                        <span><i class="fa-solid fa-tags me-2 text-muted"></i>Danh mục sản phẩm</span>
                        <i class="fa-solid fa-chevron-down transition-icon fs-7 text-secondary"></i>
                    </a>
                    <div class="collapse" id="offcanvasCategoryCollapse">
                        <ul class="list-unstyled ps-4 mt-2 border-start ms-2 text-muted">
                            <?php if (!empty($categories)) {
                                foreach ($categories as $category) { ?>
                                    <li class="py-1">
                                        <a class="text-decoration-none text-secondary d-block py-1.5 px-2 rounded-2 category-sub-link" href="<?php echo url('CategoryController/index?MaDanhMuc=' . $category['MaDanhMuc']) ?>">
                                            <?php echo htmlspecialchars($category['TenDanhMuc']); ?>
                                        </a>
                                    </li>
                            <?php }
                            } else { ?>
                                <li class="py-1 text-muted ps-2 fs-7">Chưa có danh mục</li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>

                <li class="nav-item py-1"><a class="nav-link d-flex align-items-center text-dark" href=""><i class="fa-regular fa-newspaper me-2 text-primary"></i>Tin tức & Blog</a></li>
                <li class="nav-item py-1"><a class="nav-link d-flex align-items-center text-dark" href=""><i class="fa-solid fa-award me-2 text-danger"></i>Thương hiệu</a></li>
                <li class="nav-item py-1"><a class="nav-link d-flex align-items-center text-dark" href=""><i class="fa-solid fa-store me-2 text-info"></i>Hệ thống cửa hàng</a></li>
            </ul>
            <div class="mt-auto d-flex">
                <hr class="mb-3 text-secondary">
                <div class="d-flex gap-3">
                    <a href="<?php echo url('AuthController/login') ?>" class="btn btn-primary btn-lg fw-bold shadow-sm rounded-3">
                        <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Đăng nhập
                    </a>
                    <a href="<?php echo url('AuthController/register') ?>" class="btn btn-outline-primary btn-lg fw-bold rounded-3">
                        <i class="fa-solid fa-user-plus me-2"></i>Đăng ký
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
    window.addEventListener('pageshow', function(event) {
        // Đảm bảo số lượng giỏ hàng luôn được cập nhật mới nhất từ server khi back/forward
        fetch('<?php echo url("CartController/getCount") ?>')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const badge = document.querySelector('.cart-count');
                    if (badge) {
                        badge.innerText = data.cartCount;
                    }
                }
            })
            .catch(err => console.error('Lỗi cập nhật số lượng giỏ hàng:', err));
    });
</script>