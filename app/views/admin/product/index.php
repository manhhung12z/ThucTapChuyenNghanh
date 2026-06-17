<?php require_once(_DIR_ROOT . '/app/views/admin/layouts/index.php') ?>
<?php startblock('Acss', 0) ?>
    <link href="<?= asset('assets/admin/css/styleproduct.css') ?>" rel="stylesheet">
<?php endblock() ?>

<?php startblock('Atitle', 0) ?>
    <h1 class="fs-4 fw-bold text-dark mb-0 text-truncate" style="letter-spacing: -0.02em;">Quản lý sản phẩm</h1>
<?php endblock() ?>

<?php startblock('Acontent', 0) ?>
    <div class="mb-4 d-flex gap-2">
        <button onclick="openModal('add')" class="btn btn-blue-privia d-inline-flex align-items-center gap-2">
            <span style="font-size: 1.2rem; font-weight: 300; line-height: 1;">+</span> Thêm sản phẩm mới
        </button>
        <button onclick="document.getElementById('excelFileInput').click()" class="btn btn-green-privia d-inline-flex align-items-center gap-2">
            <span style="font-size: 1.2rem; font-weight: 300; line-height: 1;">&#8681;</span> Nhập từ Excel
        </button>
        <input type="file" id="excelFileInput" accept=".xlsx, .xls" style="display: none;" onchange="handleExcelUpload(event)">
    </div>

    <div class="privia-card overflow-hidden">
        <div class="px-4 py-4 d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3" style="background-color: #ffffff;">
            <span class="fw-bold text-dark" style="font-size: 0.95rem; letter-spacing: -0.01em;">Tất cả sản phẩm</span>
            <input type="text" id="searchProduct" placeholder="Tìm kiếm sản phẩm..." class="form-control search-privia w-100 shadow-none" style="max-width: 100%;">
        </div>
    
        
        <div class="table-responsive w-100">
            <table class="table table-borderless table-privia align-middle mb-0" style="min-width: 800px;">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 25%;">TÊN SẢN PHẨM</th>
                        <th style="width: 15%;">DANH MỤC</th>
                        <th style="width: 15%;">GIÁ BÁN</th>
                        <th style="width: 15%;">TỒN KHO</th>
                        <th class="text-center" style="width: 15%;">TRẠNG THÁI</th>
                        <th class="text-center pe-4" style="width: 15%;">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <?php if (!empty($products)): 
                        foreach ($products as $pro): 
                            $proJson = json_encode([
                                'MaSanPham' => $pro['masanpham'] ?? '',
                                'TenSanPham' => $pro['tensanpham'] ?? '',
                                'MaDanhMuc' => $pro['madanhmuc'] ?? '',
                                'Gia' => $pro['gia'] ?? '',
                                'SoLuong' => $pro['tonkho'] ?? '',
                                'TrangThai' => $pro['trangthai'] ?? '',
                                'MoTa' => $pro['mota'] ?? '',
                                'HinhAnh' => $pro['hinhanh'] ?? '',
                                'MaGiamGia' => $pro['magiamgia'] ?? ''
                            ], JSON_UNESCAPED_UNICODE);
                            $proJsonHtml = htmlspecialchars($proJson, ENT_QUOTES, 'UTF-8');
                    ?>
                        <tr>
                            <td class="ps-4 fw-semibold text-dark align-middle">
                                <div class="text-truncate-custom" style="max-width: 220px;" title="<?= $pro['tensanpham'] ?>">
                                    <?= $pro['tensanpham'] ?>
                                </div>
                            </td>
                            <td class="text-muted align-middle"><?= $pro['tendanhmuc'] ?></td>
                            <td class="fw-bold text-primary align-middle" style="color: #2563eb !important;"><?= number_format($pro['gia']) ?>đ</td>
                            <td class="fw-semibold text-dark align-middle"><?= $pro['tonkho'] ?></td>
                            <td class="text-center align-middle">
                                <?php 
                                $status = $pro['trangthai'];
                                $badgeClass = 'bg-light text-secondary'; 
                                switch ($status) {
                                    case 'Còn hàng': 
                                        $badgeClass = 'bg-success-subtle text-success'; 
                                        break;
                                    case 'Bán chạy': 
                                        $badgeClass = 'bg-primary-subtle text-primary'; 
                                        break;
                                    case 'Mới': 
                                        $badgeClass = 'bg-info-subtle text-info'; 
                                        break;
                                    case 'Cao cấp': 
                                        $badgeClass = 'bg-warning-subtle text-warning-emphasis'; 
                                        break;
                                    case 'Giảm giá': 
                                        $badgeClass = 'bg-danger-subtle text-danger'; 
                                        break;
                                }
                                echo "<span class='badge $badgeClass px-2-5 py-1-5 fw-medium' style='font-size: 0.75rem; border-radius: 6px;'>$status</span>";
                                ?>
                            </td>
                            <td class="text-center pe-4 align-middle">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <button onclick='openModal("edit", <?= $proJsonHtml ?>)' class="btn btn-action-privia btn-edit-privia">Sửa</button>
                                    <?php if (!isset($_SESSION['staff'])): ?>
                                        <button onclick="deleteProduct('<?= $pro['masanpham'] ?>', this)" class="btn btn-action-privia btn-delete-privia">Xóa</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted" style="font-size: 0.9rem;">Chưa có sản phẩm nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php require_once _DIR_ROOT . '/app/views/admin/layouts/includes/pagination.php'; ?>
    </div>

    <?php require_once _DIR_ROOT . '/app/views/admin/product/modal.php'; ?>
<?php endblock() ?>

<?php startblock('Ajs', 0) ?>
    <script>const WEB_ROOT = "<?= _WEB_ROOT ?>";</script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script src="<?= asset('assets/admin/js/product.js') ?>"></script>

<?php endblock() ?>