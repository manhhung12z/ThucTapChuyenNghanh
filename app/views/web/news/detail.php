<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?php startblock('content') ?>

<style>
    .news-detail-container {
        max-width: 850px;
        margin: 0 auto;
        background: #fff;
        padding: 45px 40px;
        border-radius: 16px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.02);
        border: 1px solid #f3f4f6;
        position: relative;
    }
    /* Thanh điểm nhấn màu hồng Privia ở đầu trang bài viết */
    .news-detail-container::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #db7093, #ffb6c1);
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }
    .news-detail-badge {
        background-color: #fff1f2;
        color: #db7093;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 20px;
        display: inline-block;
        font-size: 0.85rem;
        margin-bottom: 20px;
        letter-spacing: 0.5px;
    }
    .news-detail-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1f2937;
        line-height: 1.4;
        margin-bottom: 25px;
    }
    .news-detail-divider {
        height: 1px;
        background: linear-gradient(to right, #f3f4f6, #ffb6c1, #f3f4f6);
        margin: 30px 0;
    }
    .news-detail-content {
        font-size: 1.1rem;
        color: #374151;
        line-height: 1.85;
        letter-spacing: 0.2px;
        text-align: justify;
    }
    /* Định dạng nếu nội dung có các đoạn văn */
    .news-detail-content p {
        margin-bottom: 20px;
    }
    .btn-back-magazine {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #db7093;
        background-color: #fff1f2;
        border: 1px solid transparent;
        padding: 10px 24px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-top: 20px;
    }
    .btn-back-magazine:hover {
        background-color: #db7093;
        color: #fff;
        transform: translateX(-4px);
    }
</style>

<div class="container mt-5 mb-5">
    <div class="news-detail-container">
        <div class="news-detail-badge">Tin tức & Blog</div> 

        <h1 class="news-detail-title">
            <?= htmlspecialchars($news['TieuDe']) ?>
        </h1>

        <div class="news-detail-divider"></div>

        <div class="news-detail-content">
            <?php 
                echo $news['NoiDung']; 
            ?>
        </div>

        <div class="news-detail-divider"></div>

        <div class="text-start">
            <a href="<?= url('NewsController') ?>" class="btn-back-magazine">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
</div>

<?php endblock() ?>