<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?php startblock('content') ?>

<style>
    .news-header-banner {
        background: linear-gradient(rgba(219, 112, 147, 0.1), rgba(255, 182, 193, 0.15));
        padding: 50px 0;
        text-align: center;
        border-radius: 12px;
        margin-bottom: 40px;
    }
    .news-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        position: relative;
    }
    /* Thêm thanh màu gradient mỏng ở trên cùng thay cho hình ảnh */
    .news-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #db7093, #ffb6c1);
    }
    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(219,112,147,0.12);
        border-color: #ffb6c1;
    }
    .news-body {
        padding: 35px 25px 30px; /* Tăng padding trên lên một chút để cân đối layout không hình */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-grow: 1;
    }
    .news-title {
        font-size: 1.25rem; /* Tăng size chữ tiêu đề một chút */
        font-weight: 700;
        color: #1f2937;
        line-height: 1.5;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .news-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }
    .news-title a:hover {
        color: #db7093;
    }
    .news-desc {
        font-size: 0.95rem; /* Tăng size chữ nội dung một chút */
        color: #6b7280;
        line-height: 1.7;
        margin-bottom: 25px;
        display: -webkit-box;
        -webkit-line-clamp: 4; /* Cho phép hiển thị 4 dòng vì không có hình */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .btn-readmore {
        color: #db7093;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: gap 0.2s;
    }
    .btn-readmore:hover {
        color: #b84a6c;
        gap: 8px;
    }
    .pagination-user .page-link {
        color: #db7093;
        border-color: #f3f4f6;
        padding: 10px 18px;
        margin: 0 3px;
        border-radius: 8px;
        box-shadow: none;
    }
    .pagination-user .page-item.active .page-link {
        background-color: #db7093;
        border-color: #db7093;
        color: #fff;
    }
    .pagination-user .page-link:hover {
        background-color: #fff1f2;
        color: #db7093;
        border-color: #ffb6c1;
    }
</style>

<div class="container mt-4 mb-5">
    <div class="news-header-banner">
        <h1 class="fw-bold" style="color: #333; letter-spacing: 1px;">Tin tức & Blog mới nhất của PRIVIA</h1>
        <p class="text-muted mb-0">Bí quyết chăm sóc da chuyên sâu và xu hướng làm đẹp chuẩn Hàn</p>
    </div>

    <div class="row g-4">
        <?php if (!empty($news_list)): ?>
            <?php foreach ($news_list as $news): 
                $plain_text = strip_tags($news['NoiDung']);
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="news-card">
                        <div class="news-body">
                            <div>
                                <h4 class="news-title">
                                    <a href="<?= url('NewsController/detail?id=' . urlencode($news['MaTinTuc'])) ?>">
                                        <?= htmlspecialchars($news['TieuDe']) ?>
                                    </a>
                                </h4>
                                <p class="news-desc">
                                    <?= htmlspecialchars($plain_text) ?>
                                </p>
                            </div>
                            <div>
                                <a href="<?= url('NewsController/detail?id=' . urlencode($news['MaTinTuc'])) ?>" class="btn-readmore">
                                    Xem chi tiết <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">Hiện tại chưa có bài viết tin tức nào.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <nav class="d-flex justify-content-center mt-5 mb-5">
            <ul class="pagination pagination-user">
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= url('NewsController?page=' . ($currentPage - 1)) ?>">&larr;</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="<?= url('NewsController?page=' . $i) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?> 

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= url('NewsController?page=' . ($currentPage + 1)) ?>">&rarr;</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php endblock() ?>