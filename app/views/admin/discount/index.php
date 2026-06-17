<?php require_once(_DIR_ROOT . '/app/views/admin/layouts/index.php') ?>

<?php startblock('Acss', 0) ?>
    <link href="<?= asset('assets/admin/css/stylediscount.css') ?>" rel="stylesheet">
<?php endblock() ?>

<?php startblock('Atitle', 0) ?>
    <h1 class="fs-4 fw-bold text-dark mb-0 text-truncate">Quản lý mã giảm giá</h1>
<?php endblock() ?>

<?php startblock('Acontent', 0) ?>
    <div class="mb-4">
        <button onclick="openModal('add')" class="btn btn-gradient-privia d-inline-flex align-items-center gap-2">
            <span style="font-size: 1.1rem; font-weight: 300;">+</span> Thêm giảm giá mới
        </button>
    </div>

    <div class="privia-card overflow-hidden">
        
        <div class="px-4 py-4 d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
            <span class="fw-bold text-dark" style="font-size: 0.95rem; color: #1f2937 !important;">Tất cả chương trình giảm giá</span>
            <input type="text" 
                   id="searchDiscount"
                   placeholder="Tìm kiếm giảm giá..." 
                   class="form-control search-privia w-100 shadow-none" 
                   style="max-width: 100%;">
        </div>

        <div class="table-responsive w-100">
            <table class="table table-borderless table-privia align-middle mb-0" style="min-width: 760px;">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 20%;">TÊN CHƯƠNG TRÌNH</th>
                        <th style="width: 15%;">MỨC GIẢM (%)</th>
                        <th style="width: 15%;">SỐ SẢN PHẨM</th>
                        <th class="text-center" style="width: 20%;">THỜI GIAN ÁP DỤNG</th>
                        <th class="text-center pe-4" style="width: 15%;">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody id="discountTableBody">
                    <?php 
                    if (!empty($discounts)): 
                        foreach ($discounts as $dis): 
                            $disJson = json_encode([
                                'MaGiamGia' => $dis['magiamgia'] ?? '',
                                'TenGiamGia' => $dis['tengiamgia'] ?? '',
                                'PhanTram' => $dis['phantram'] ?? 0,
                                'NgayBatDau' => $dis['ngaybatdau'] ?? '',
                                'NgayKetThuc' => $dis['ngaykethuc'] ?? ''
                            ], JSON_UNESCAPED_UNICODE);
                            $disJsonHtml = htmlspecialchars($disJson, ENT_QUOTES, 'UTF-8');
                    ?>
                            <td class="ps-4 fw-semibold text-dark" style="color: #111827 !important;">
                                <?php echo $dis['tengiamgia']; ?>
                            </td>

                            <td class="fw-bold" style="color: #d97706;">
                                <?php echo $dis['phantram']; ?>%
                            </td>
                            <td class="text-secondary fw-medium">
                                <?php echo $dis['tongsanpham']; ?>
                            </td>
                            <td class="text-center text-secondary small" style="font-size: 0.8rem; line-height: 1.5;">
                                <div><span class="text-success fw-medium">Từ:</span> <?php echo date('d/m/Y H:i', strtotime($dis['ngaybatdau'])); ?></div>
                                <div><span class="text-danger fw-medium">Đến:</span> <?php echo date('d/m/Y H:i', strtotime($dis['ngaykethuc'])); ?></div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <button onclick="openModal('edit', <?= $disJsonHtml ?>)" class="btn btn-action-privia btn-edit-privia">Sửa</button>
                                    <button onclick="deleteDiscount('<?php echo $dis['magiamgia']; ?>', this)" class="btn btn-action-privia btn-delete-privia">Xóa</button>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted" style="font-size: 0.9rem;">
                                Chưa có chương trình khuyến mãi nào hoạt động trên hệ thống Privia.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> 
        
        <?php require_once _DIR_ROOT . '/app/views/admin/layouts/includes/pagination.php'; ?>
    </div>

    <?php require_once _DIR_ROOT . '/app/views/admin/discount/modal.php'; ?>
<?php endblock() ?>

<?php startblock('Ajs', 0) ?>
    <script>const WEB_ROOT = "<?= _WEB_ROOT ?>";</script>
    <script src="<?= asset('assets/admin/js/discount.js') ?>"></script>
<?php endblock() ?>