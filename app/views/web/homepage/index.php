<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?php startblock('content') ?>
<div class="container">
    <div class="promo-section mt-2">
        <div class="banner-grid">

            <!-- Banner lớn -->
            <div class="banner-left">
                <div class="slider">
                    <div class="slides">
                        <div class="slide active">
                            <a href="#"><img src="<?php echo asset('assets/web/img/banner.jpg') ?>" alt="banner1"></a>
                        </div>
                        <div class="slide">
                            <a href="#"><img src="<?php echo asset('assets/web/img/banner1.jpg') ?>" alt="banner2"></a>
                        </div>
                        <div class="slide">
                            <a href="#"><img src="<?php echo asset('assets/web/img/banner2.png') ?>" alt="banner3"></a>
                        </div>
                    </div>

                    <div class="dots">
                        <span class="dot active"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                </div>
            </div>

            <!-- Banner nhỏ -->
            <div class="banner-right">
                <div class="banner-small">
                    <a href="#"><img src="<?php echo asset('assets/web/img/bannersmall.png') ?>" class="rounded"></a>
                </div>

                <div class="banner-small">
                    <a href="#"><img src="<?php echo asset('assets/web/img/bannersmall1.png') ?>" class="rounded"></a>
                </div>
            </div>

        </div>
    </div>

    <div class="category-wrapper" style="margin-top: 30px;">
        <div class="category-list">
            <a href="#" class="category-item">
                <div onclick="toggleChatbot()" class="icon"><i class="fa-solid fa-headset"></i></div>
                <p>Hỗ trợ</p>
            </a>
            <a href="<?php echo url('CategoryController/index?MaDanhMuc=DM_SON'); ?>" class="category-item">
                <div class="icon"><i class="fa-solid fa-heart"></i></i></div>
                <p>Son môi</p>
            </a>

            <a href="<?php echo url('PolicyController/index'); ?>" class="category-item">
                <div class="icon"><i class="fa-solid fa-fire"></i></div>
                <p>Chính sách</p>
            </a>

            <a href="<?php echo url('CategoryController/index?MaDanhMuc=DM_MAKE'); ?>" class="category-item">
                <div class="icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                <p>Make up</p>
            </a>

            <a href="<?php echo url('CategoryController/index?MaDanhMuc=DM_SKIN'); ?>" class="category-item">
                <div class="icon"><i class="fa-solid fa-pump-soap"></i></div>
                <p>Skincare</p>
            </a>

            <a href="<?php echo url('CategoryController/index?MaDanhMuc=DM_PERFUME'); ?>" class="category-item">
                <div class="icon"><i class="fa-solid fa-spray-can"></i></div>
                <p>Nước hoa</p>
            </a>

            <a href="<?php echo url('NewsController/index') ?>" class="category-item">
                <div class="icon"><i class="fa-solid fa-book"></i></div>
                <p>Blog</p>
            </a>
        </div>

    </div>
    <div class="brand-slider" style="margin-top: 20px;">
        <div class="brand-track">
            <div class="brand-item">
                <img src="<?php echo asset('assets/web/img/product-banner1.png') ?>">
            </div>
            <div class="brand-item">
                <img src="<?php echo asset('assets/web/img/product-banner2.png') ?>">
            </div>
            <div class="brand-item">
                <img src="<?php echo asset('assets/web/img/product-banner3.png') ?>">
            </div>
            <div class="brand-item">
                <img src="<?php echo asset('assets/web/img/product-banner1.png') ?>">
            </div>
            <div class="brand-item">
                <img src="<?php echo asset('assets/web/img/product-banner2.png') ?>">
            </div>
            <div class="brand-item">
                <img src="<?php echo asset('assets/web/img/product-banner3.png') ?>">
            </div>
        </div>
    </div>
     <h3 class="title-product mt-5" style="text-align: center;">Sản phẩm khuyến mãi</h3>
     <p class="text-center" style="color: #666;">.................................................................................................</p>
    <div class="product-slider">
        <button class="arrow left">&#10094;</button>
        <div class="imagecard gap-4">
            <?php if (!empty($data_sp)) {
                foreach ($data_sp as $product) { ?>
                    <a href="<?php echo url('ProductController?MaSanPham=' . $product['MaSanPham']) ?>"
                        style="text-decoration: none; color: inherit;">
                        <div class="card_image">
                            <div class="discount-badge">
                                -<?php echo $product['PhanTram'] ?>%
                            </div>
                            <img src="<?php echo $product['HinhAnh'] ?>" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['MaDanhMuc'] ?></h5>
                                <p class="card-text"><?php echo $product['TenSanPham'] ?></p>
                                <div class="price-box">
                                    <span class="old-price">
                                        <?php echo number_format($product['Gia']) ?>đ
                                    </span>

                                    <span class="new-price">
                                        <?php echo number_format($product['GiaSauGiam']) ?>đ
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
            <?php }
            } ?>
        </div>
        <button class="arrow right">&#10095;</button>
    </div>
</div>
<section class="about-privia py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-image-box">
                    <img src="<?php echo asset('assets/web/img/about-privia.png') ?>"
                        alt="PRIVIA Skincare"
                        class="about-img">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <span class="about-subtitle">About Privia</span>
                    <h2>
                        Vẻ đẹp thuần khiết <br>
                        <span>chạm đến tự tin</span>
                    </h2>

                    <p>
                        PRIVIA mang đến các sản phẩm chăm sóc da chuẩn Hàn Quốc,
                        dịu nhẹ, an toàn và phù hợp với nhiều loại da. Chúng tôi
                        mong muốn giúp bạn chăm sóc làn da mỗi ngày một cách tự tin,
                        tinh tế và hiệu quả hơn.
                    </p>
                    <div class="about-stats">
                        <div>
                            <h3>500K+</h3>
                            <span>Khách hàng tin dùng</span>
                        </div>

                        <div>
                            <h3>50+</h3>
                            <span>Sản phẩm chất lượng</span>
                        </div>

                        <div>
                            <h3>98%</h3>
                            <span>Phản hồi tích cực</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <h3 class="title-product mt-5" style="text-align: center;">Sản phẩm bán chạy</h3>
    <p class="text-center" style="color: #666;">.................................................................................................</p>
    <div class="product-best-slider mt-4">
        <div class="imagecard_copy gap-4">
            <?php if (!empty($featured_products)) {
                foreach ($featured_products as $product) { ?>
                    <a href="<?php echo url('ProductController?MaSanPham=' . $product['MaSanPham']) ?>"
                        style="text-decoration: none; color: inherit;">
                        <div class="card_image">
                            <div class="discount-badge">
                                -<?php echo $product['PhanTram'] ?>%
                            </div>
                            <img src="<?php echo $product['HinhAnh'] ?>" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['MaDanhMuc'] ?></h5>
                                <p class="card-text"><?php echo $product['TenSanPham'] ?></p>
                                <div class="price-box">
                                    <span class="old-price">
                                        <?php echo number_format($product['Gia']) ?>đ
                                    </span>

                                    <span class="new-price">
                                        <?php echo number_format($product['GiaSauGiam']) ?>đ
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
            <?php }
            } ?>
        </div>
    </div>
    <div class="commitments-section py-2">
        <div class="row text-center gy-4">
            <div class="col-6 col-lg-3">
                <div class="commitment-card">
                    <div class="commitment-icon bg-light-blue">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h5 class="fw-bold mt-3 mb-2" style="font-size: 1rem;">100% Chính Hãng</h5>
                    <p class="text-muted small mb-0">Cam kết hoàn tiền 200% nếu phát hiện hàng giả.</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="commitment-card">
                    <div class="commitment-icon bg-light-purple">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h5 class="fw-bold mt-3 mb-2" style="font-size: 1rem;">Giao Nhanh Siêu Tốc</h5>
                    <p class="text-muted small mb-0">Nhận hàng nhanh chóng trong 2-4 giờ làm việc.</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="commitment-card">
                    <div class="commitment-icon bg-light-pink">
                        <i class="fa-solid fa-rotate-left"></i>
                    </div>
                    <h5 class="fw-bold mt-3 mb-2" style="font-size: 1rem;">Đổi Trả 7 Ngày</h5>
                    <p class="text-muted small mb-0">Dễ dàng đổi trả sản phẩm lỗi trong vòng 7 ngày.</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="commitment-card">
                    <div class="commitment-icon bg-light-orange">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h5 class="fw-bold mt-3 mb-2" style="font-size: 1rem;">Tư Vấn Tận Tâm</h5>
                    <p class="text-muted small mb-0">Đội ngũ tư vấn viên sẵn sàng hỗ trợ 24/7.</p>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="chatbot-toggle" onclick="toggleChatbot()">💬</div>
<div class="chatbot-box" id="chatbotBox">
    <div class="chatbot-header">
        Chat với shop
        <span onclick="toggleChatbot()">×</span>
    </div>

    <div class="chatbot-messages" id="chatMessages">
        <div class="bot-message">Dạ em chào anh/chị, em có thể hỗ trợ gì ạ?</div>
    </div>
    <div class="chatbot-input">
        <input type="text" id="chatInput" placeholder="Nhập câu hỏi...">
        <button onclick="sendMessage()">Gửi</button>
    </div>

</div>
<?php endblock() ?>