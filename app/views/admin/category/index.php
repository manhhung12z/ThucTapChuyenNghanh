<?php require_once(_DIR_ROOT . '/app/views/admin/layouts/index.php') ?>
<?php startblock('Acss', 0) ?>
    <link href="<?= asset('assets/admin/css/stylecategory.css') ?>" rel="stylesheet">
<?php endblock() ?>

<?php startblock('Atitle', 0) ?>
<h1 class="fs-4 fw-bold text-dark mb-0 text-truncate" style="letter-spacing: -0.02em;">Quản lý danh mục</h1>
<?php endblock() ?>

<?php startblock('Acontent', 0) ?>


    <div class="mb-4">
        <button onclick="openModal('add')" class="btn btn-luxury-privia d-inline-flex align-items-center gap-2">
            <span style="font-size: 1.2rem; font-weight: 300; line-height: 1;">+</span> Thêm danh mục mới
        </button>
    </div>

    <div class="privia-card overflow-hidden">
        
        <div class="px-4 py-4 d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3" style="background-color: #ffffff;">
            <span class="fw-bold text-dark" style="font-size: 0.95rem; letter-spacing: -0.01em;">Tất cả danh mục</span>
            <input type="text" 
                   id="searchCategory"
                   placeholder="Tìm kiếm danh mục..." 
                   class="form-control search-privia w-100 shadow-none" 
                   style="max-width: 100%;">
        </div>

        <div class="table-responsive w-100">
            <table class="table table-borderless table-privia align-middle mb-0" style="min-width: 700px;">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 25%;">TÊN DANH MỤC</th>
                        <th style="width: 15%;">SỐ SẢN PHẨM</th>
                        <th style="width: 45%;">MÔ TẢ</th>
                        <th class="text-center pe-4" style="width: 15%;">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody">
                    <?php 
                    if (!empty($categories)): 
                        foreach ($categories as $cat): 
                            $catJson = json_encode([
                                'MaDanhMuc' => $cat['madanhmuc'] ?? '',
                                'TenDanhMuc' => $cat['tendanhmuc'] ?? '',
                                'MoTa' => $cat['mota'] ?? ''
                            ], JSON_UNESCAPED_UNICODE);
                            $catJsonHtml = htmlspecialchars($catJson, ENT_QUOTES, 'UTF-8');
                    ?>
                        <tr data-id="<?php echo $cat['madanhmuc']; ?>">
                            <td class="ps-4 fw-semibold text-dark" style="color: #000000 !important;">
                                <?php echo $cat['tendanhmuc']; ?>
                            </td>
                            <td class="fw-bold" style="color: #4b5563;">
                                <?php echo $cat['tongsanpham']; ?>
                            </td>
                            <td>
                                <div class="desc-col">
                                    <?php echo !empty($cat['mota']) ? $cat['mota'] : '<span style="color: #d1d5db; font-style: italic;">Chưa có mô tả</span>'; ?>
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <button onclick="openModal('edit', <?= $catJsonHtml ?>)" class="btn btn-action-privia btn-edit-privia">Sửa</button>
                                    <?php if (!isset($_SESSION['staff'])): ?>
                                        <button onclick="deleteCategory('<?php echo $cat['madanhmuc']; ?>', this)" class="btn btn-action-privia btn-delete-privia">Xóa</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted" style="font-size: 0.9rem;">
                                Chưa có danh mục mỹ phẩm nào trong hệ thống Privia.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> 
        
        <?php require_once _DIR_ROOT . '/app/views/admin/layouts/includes/pagination.php'; ?>
    </div>

    <?php require_once _DIR_ROOT . '/app/views/admin/category/modal.php'; ?>
<?php endblock() ?>

<?php startblock('Ajs', 0) ?>
    <script>const WEB_ROOT = "<?= _WEB_ROOT ?>";</script>
    <script src="<?= asset('assets/admin/js/category.js') ?>"></script>
<?php endblock() ?>