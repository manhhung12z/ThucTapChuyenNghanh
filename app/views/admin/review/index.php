<?php require_once(_DIR_ROOT . '/app/views/admin/layouts/index.php') ?>
<?php startblock('Acss', 0) ?>
    <link href="<?= asset('assets/admin/css/stylereview.css') ?>" rel="stylesheet">
<?php endblock() ?>

<?php startblock('Atitle', 0) ?>
    <h1 class="fs-4 fw-bold text-dark mb-0 text-truncate">Quản lý đánh giá</h1>
<?php endblock() ?>

<?php startblock('Acontent', 0) ?>
    <div class="mb-4"></div>

    <div class="privia-card overflow-hidden">
        
        <div class="px-4 py-4 d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
            <span class="fw-bold text-dark" style="font-size: 0.95rem; color: #1f2937 !important;">Tất cả bình luận phản hồi</span>
            <input type="text" 
                   id="searchReview"
                   placeholder="Tìm tên khách, sản phẩm..." 
                   class="form-control search-privia w-100 shadow-none" 
                   style="max-width: 260px;">
        </div>

        <div class="table-responsive w-100">
            <table class="table table-borderless table-privia align-middle mb-0" style="min-width: 850px; table-layout: fixed;">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 20%;">KHÁCH HÀNG</th>
                        <th style="width: 25%;">SẢN PHẨM</th>
                        <th class="text-center" style="width: 15%;">SỐ SAO</th>
                        <th style="width: 25%;">NỘI DUNG BÌNH LUẬN</th>
                        <th class="text-center pe-4" style="width: 15%;">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody id="reviewTableBody">
                    <?php 
                    if (!empty($reviews)): 
                        foreach ($reviews as $rev): 
                            $revJson = json_encode([
                                'MaDanhGia' => $rev['madanhgia'] ?? '',
                                'SoSao' => $rev['sosao'] ?? 5,
                                'NoiDung' => $rev['noidung'] ?? ''
                            ], JSON_UNESCAPED_UNICODE);
                            $revJsonHtml = htmlspecialchars($revJson, ENT_QUOTES, 'UTF-8');
                    ?>
                        <tr data-id="<?php echo $rev['madanhgia']; ?>">
                            <td class="ps-4 fw-semibold text-dark truncate-col" style="color: #111827 !important;">
                                <?php echo $rev['tennguoidung']; ?>
                            </td>
                            
                            <td class="text-secondary fw-medium truncate-col">
                                <?php echo $rev['tensanpham']; ?>
                            </td>
                            
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center fw-bold gap-0.5" style="color: #ffb703; font-size: 1rem;">
                                    <?php 
                                    $stars = (int)($rev['sosao'] ?? 5);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $stars ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                            </td>
                            
                            <td class="text-muted truncate-col">
                                <?php echo !empty($rev['noidung']) ? $rev['noidung'] : '<span class="text-light-emphasis text-opacity-50 text-muted fst-italic">Không có nội dung văn bản</span>'; ?>
                            </td>
                            
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <button onclick="deleteReview('<?php echo $rev['madanhgia']; ?>', this)" class="btn btn-action-privia btn-delete-privia">Xóa</button>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted" style="font-size: 0.9rem;">
                                Hệ thống Privia chưa nhận được đánh giá nào từ khách hàng.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> 
        
        <?php require_once _DIR_ROOT . '/app/views/admin/layouts/includes/pagination.php'; ?>
    </div>
<?php endblock() ?>

<?php startblock('Ajs', 0) ?>
    <script>
        const WEB_ROOT = "<?= _WEB_ROOT ?>";
    </script>
    <script src="<?= asset('assets/admin/js/review.js') ?>"></script>
<?php endblock() ?>