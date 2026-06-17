<?php require_once(_DIR_ROOT . '/app/views/admin/layouts/index.php') ?>
<?php startblock('Acss', 0) ?>
    <link href="<?= asset('assets/admin/css/stylenews.css') ?>" rel="stylesheet">
<?php endblock() ?>

<?php startblock('Atitle', 0) ?>
    <h1 class="fs-4 fw-bold text-dark mb-0 text-truncate">Quản lý tin tức</h1>
<?php endblock() ?>

<?php startblock('Acontent', 0) ?>
    <div class="mb-4">
        <button onclick="openModal('add')" class="btn btn-gradient-privia d-inline-flex align-items-center gap-2">
            <span style="font-size: 1.1rem; font-weight: 300;">+</span> Thêm bài viết mới
        </button>
    </div>

    <div class="privia-card overflow-hidden">
        
        <div class="px-4 py-4 d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
            <span class="fw-bold text-dark" style="font-size: 0.95rem; color: #1f2937 !important;">Tất cả bài viết</span>
            <input type="text" 
                   id="searchNews"
                   placeholder="Tìm kiếm tiêu đề, nội dung..." 
                   class="form-control search-privia w-100 shadow-none" 
                   style="max-width: 100%;">
        </div>

        <div class="table-responsive w-100">
            <table class="table table-borderless table-privia align-middle mb-0" style="min-width: 850px; table-layout: fixed;">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 15%;">MÃ TIN TỨC</th>
                        <th style="width: 35%;">TIÊU ĐỀ BÀI VIẾT</th>
                        <th style="width: 35%;">NỘI DUNG TÓM TẮT</th>
                        <th class="text-center pe-4" style="width: 15%;">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody id="newsTableBody">
                    <?php 
                    if (!empty($newsList)): 
                        foreach ($newsList as $news): 
                            $newsJson = json_encode([
                                'MaTinTuc' => $news['matintuc'] ?? '',
                                'Tieude' => $news['tieude'] ?? '',
                                'NoiDung' => $news['noidung'] ?? ''
                            ], JSON_UNESCAPED_UNICODE);
                            $newsJsonHtml = htmlspecialchars($newsJson, ENT_QUOTES, 'UTF-8');
                    ?>
                        <tr data-id="<?php echo $news['matintuc']; ?>">
                            <td class="ps-4 fw-medium text-secondary">
                                #<?php echo $news['matintuc']; ?>
                            </td>
                            
                            <td class="fw-semibold text-dark truncate-col" style="color: #111827 !important;">
                                <?php echo $news['tieude']; ?>
                            </td>
                            
                            <td>
                                <div class="line-clamp-2">
                                    <?php echo strip_tags($news['noidung']); ?>
                                </div>
                            </td>
                            
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <button onclick="openModal('edit', <?= $newsJsonHtml ?>)" class="btn btn-action-privia btn-edit-privia">Sửa</button>
                                    <button onclick="deleteNews('<?php echo $news['matintuc']; ?>', this)" class="btn btn-action-privia btn-delete-privia">Xóa</button>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted" style="font-size: 0.9rem;">
                                Chưa có bài viết tin tức nào trong hệ thống Privia.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> 
        
        <?php require_once _DIR_ROOT . '/app/views/admin/layouts/includes/pagination.php'; ?>
    </div>

    <?php require_once _DIR_ROOT . '/app/views/admin/news/modal.php'; ?>
<?php endblock() ?>

<?php startblock('Ajs', 0) ?>
    <script>
        const WEB_ROOT = "<?= _WEB_ROOT ?>";
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script src="<?= asset('assets/admin/js/news.js') ?>"></script>
<?php endblock() ?>