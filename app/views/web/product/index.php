<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?php require_once(_DIR_ROOT . '/app/Controllers/Web/AuthController.php') ?>
<?php
$currentUser = auth::getUser('user');
?>
<?php startblock('content') ?>

<div class="container">
    <form action="<?php echo url('OrderController/prepare') ?>" method="POST">
        <div class="product-container">
            <div class="product-left">
                <img src="<?php echo $data['HinhAnh'] ?? 'Không có mô tả cho sản phẩm này.' ?>" class="main-img">
                <div class="thumb-list">
                    <img src="<?php echo $data['HinhAnh'] ?? 'Không có mô tả cho sản phẩm này.'  ?>">
                    <img src="<?php echo $data['HinhAnh'] ?? 'Không có mô tả cho sản phẩm này.'  ?>">
                </div>
            </div>
            <div class="product-right">
                <h2 class="title"><?php echo $data['TenSanPham'] ?? 'Sản phẩm không tồn tại' ?></h2>
                <p class="product-code">Mã sản phẩm: <strong><?php echo $data['MaSanPham'] ?? 'N/A' ?></strong></p>
                <div class="sprice-box">
                    <span class="discount mb-2" name="discount">giảm<?php echo $data['PhanTram'] ?? 0 ?>%</span>
                    <div class="price">
                        <input type="hidden" name="GiaSauGiam" value="<?php echo $data['GiaSauGiam'] ?? 0 ?>">
                        <?php echo number_format($data['GiaSauGiam'] ?? 0) ?> VNĐ
                    </div>
                </div>
                <div class="benefits">
                    <div class="item">
                        <i class="fa-solid fa-shield"></i>
                        <span>100% Chính hãng</span>
                    </div>
                    <div class="item">
                        <i class="fa-solid fa-truck"></i>
                        <span>Freeship từ đơn 299K</span>
                    </div>
                    <div class="item">
                        <i class="fa-solid fa-box"></i>
                        <span>Đổi trả 7 ngày</span>
                    </div>
                </div>
                <div class="quantity">
                    <div class="number1">
                        <p>Số lượng</p>
                    </div>
                    <div class="number2">
                        <button type="button" class="btn-qty-minus1"><i class="fa-solid fa-minus"></i></button>
                        <input type="text" value="1" name="number_detail">
                        <button type="button" class="btn-qty-plus1"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    <!-- Trường ẩn (hidden) để gửi ID sản phẩm sang OrderController -->
                    <input type="hidden" name="MaSanPham" value="<?php echo $data['MaSanPham'] ?? ''; ?>">
                </div>
                <div class="actions">
                    <!-- Dùng thuộc tính formaction để ghi đè action của thẻ form, ép gửi data sang CartController -->
                    <button type="button" name="submit_action" value="add_cart" class="add-cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        Thêm vào giỏ
                    </button>
                    <?php if ($currentUser) { ?>
                        <button type="submit" name="submit_action" value="buy_now" class="buy-now">
                            <i class="fa-solid fa-bolt"></i>
                            Mua ngay
                        </button>
                    <?php } else { ?>
                        <button type="button" class="buy-now" onclick="showLoginToast()">
                            <i class="fa-solid fa-bolt"></i>
                            Mua ngay
                        </button>
                    <?php } ?>

                </div>
            </div>

        </div>
    </form>
    <div class="product-detail">
        <div class="tabs">
            <div class="tab active" data-tab="intro">Mô tả Sản Phẩm</div>
            <div class="tab" data-tab="review">Đánh giá</div>
        </div>
        <div class="tab-content active" id="intro">
            <?php echo $data['MoTa'] ?? 'Không có mô tả cho sản phẩm này.' ?>
        </div>
        <div class="tab-content" id="review">
            <div class="review-section">
                <div class="review-summary">
                    <?php foreach ($reviewSummary as $reviewsy) { ?>
                        <div class="score-box">
                            <div class="score"><?php echo $reviewsy['DiemTrungBinh'] ?? 0 ?><span>/5</span></div>
                            <div class="stars">
                                <?php
                                $avg = round($reviewsy['DiemTrungBinh']);
                                echo str_repeat('★', $avg);
                                echo str_repeat('☆', 5 - $avg);
                                ?>
                            </div>
                            <p>(<?php echo $reviewsy['TongDanhGia']; ?> đánh giá)</p>
                            <small>💗 98% khách hàng hài lòng</small>
                        </div>
                        <div class="rating-bars">
                            <?php for ($i = 5; $i >= 1; $i--) {
                                $count = $reviewsy['Sao' . $i] ?? 0;
                                $total = $reviewsy['TongDanhGia'] ?: 1;
                                $percent = round(($count / $total) * 100);
                            ?>
                                <div class="bar-now">
                                    <span><?php echo $i; ?> sao</span>
                                    <div class="bar">
                                        <div style="width: <?php echo $percent; ?>%;"></div>
                                    </div>

                                    <strong><?php echo $count; ?></strong>

                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="review-list">
                    <?php if (!empty($reviews)) {
                        foreach ($reviews as $review) { ?>

                            <div class="review-item">
                                <div class="avatar">
                                    <?php echo strtoupper(substr($review['TenNguoiDung'], 0, 1)); ?>
                                    <!-- substr hàm lấy chữ cái đầu bắt đầu =0 độ dài 1 hàm strouper chuyển thành chữ hoa-->
                                </div>

                                <div class="review-content">
                                    <div class="review-top">
                                        <div>
                                            <strong>
                                                <?php echo htmlspecialchars($review['TenNguoiDung']); ?>
                                            </strong>

                                            <div class="small-stars">
                                                <?php
                                                echo str_repeat('★', $review['SoSao']);
                                                echo str_repeat('☆', 5 - $review['SoSao']);
                                                ?>
                                            </div>
                                        </div>

                                        <span>
                                            <?php echo date('d/m/Y', strtotime($review['NgayDanhGia'])); ?>
                                        </span>
                                    </div>

                                    <span class="verified">
                                        <i class="fa-solid fa-circle-check"></i>
                                        Đã mua hàng
                                    </span>

                                    <p>
                                        <?php echo htmlspecialchars($review['NoiDung']); ?>
                                    </p>
                                </div>
                            </div>

                        <?php }
                    } else { ?>
                        <p class="empty-review">Chưa có đánh giá nào cho sản phẩm này.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endblock() ?>
<?Php startblock('js') ?>
<script>
    //minus
    document.querySelector('.btn-qty-minus1').addEventListener('click', function() {
        var qtyInput1 = this.nextElementSibling;
        var currentValue1 = parseInt(qtyInput1.value);
        if (currentValue1 > 1) {
            qtyInput1.value = currentValue1 - 1;
        }
    });
    //plus
    document.querySelector('.btn-qty-plus1').addEventListener('click', function() {
        var qtyInput1 = this.previousElementSibling;
        var currentValue1 = parseInt(qtyInput1.value);
        qtyInput1.value = currentValue1 + 1;
    });

    function showLoginToast() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'warning',
            title: 'Vui lòng đăng nhập',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true
        });

        setTimeout(() => {
            window.location.href = "<?php echo url('AuthController/login') ?>";
        }, 1000);
    }
</script>
<?php endblock() ?>