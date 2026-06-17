<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?php require_once(_DIR_ROOT . '/app/Controllers/Web/AuthController.php') ?>
<?php
$currentUser = auth::getUser('user');
?>
<?php startblock('content') ?>
<div class="container cart-page-container py-3">
    <h3 class="mb-4 cart-page-title"><i class="fa-solid fa-cart-shopping cart-icon text-primary me-2"
            style="color:#1B5DEC; margin-right: 10px;"></i> Giỏ hàng</h3>
    <?php if (empty($cartItems)) { ?>
        <div class="text-center py-5 empty-cart-wrapper">
            <h4 class="empty-cart-text text-muted">Giỏ hàng trống</h4>
            <a href="<?php echo url('HomepageController/index') ?>" class="btn btn-primary mt-3 btn-continue-shopping">Tiếp
                tục mua sắm</a>
        </div>
    <?php } else { ?>
        <form action="<?php echo url('OrderController/prepare') ?>" method="POST">
            <input type="hidden" name="cart_checkout" value="1">
            <div class="row g-4 cart-content-row">
                <div class="col-lg-8 cart-table-column">
                    <div class="cart-table-wrapper">
                        <table class="table align-middle table-cart">
                            <thead>
                                <tr>
                                    <th scope="col" class="th-product"><input type="checkbox" id="checkAll"
                                            class="check-all-input me-2" checked>
                                        Sản phẩm</th>
                                    <th scope="col" class="text-center th-price">Giá</th>
                                    <th scope="col" class="text-center th-quantity">Số lượng</th>
                                    <th scope="col" class="text-center th-actions">Xoá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalAmount = 0;
                                foreach ($cartItems as $item):
                                    $gia = $item['GiaSauGiam'] ?? 0;
                                    $soLuong = $item['SoLuongMua'] ?? 0;
                                    $thanhTien = $gia * $soLuong;
                                    $totalAmount += $thanhTien;
                                    $maSanPham = $item['MaSanPham'];
                                    $hinhAnh = $item['HinhAnh'] ?? '';
                                ?>
                                    <tr class="cart-item-row">
                                        <td class="td-product">
                                            <div class="product-info-wrapper d-flex align-items-center">
                                                <input type="checkbox" name="MaSanPham[]" value="<?php echo $maSanPham; ?>"
                                                    class="item-check" data-price="<?php echo $gia; ?>" checked>
                                                <img src="<?php echo $hinhAnh ?>" class="product-img" name="HinhAnh">
                                                <div class="product-name">
                                                    <?php echo htmlspecialchars($item['TenSanPham'] ?? ''); ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center td-price"><span
                                                class="price-text text-muted fw-medium"><?php echo number_format($gia, 0, ',', '.'); ?>đ</span>
                                        </td>
                                        <td class="text-center td-qty">
                                            <div class="qty-control d-inline-flex align-items-center">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary btn-qty-minus">-</button>
                                                <input type="number" name="SoLuong[<?php echo $maSanPham; ?>]"
                                                    class="cart-qty-input" value="<?php echo $soLuong; ?>" min="1"
                                                    style="width:50px;text-align:center">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary btn-qty-plus">+</button>
                                            </div>
                                        </td>
                                        <td class="text-center td-action">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-cart"
                                                data-id="<?php echo $maSanPham; ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="col-lg-4 summary-column">
                    <div class="card summary-card p-4 shadow-sm border-0">
                        <h5 class="summary-title fw-bold mb-4"><i class="fa-solid fa-tag" style="color:#1B5DEC"></i> Thông
                            tin đơn hàng</h5>
                        <div class="summary-row d-flex justify-content-between">
                            <span class="summary-label text-muted">Tạm tính:</span>
                            <span class="summary-value fw-medium"
                                id="subtotalPrice"><?php echo number_format($totalAmount, 0, ',', '.'); ?>đ</span>
                        </div>
                        <div class="summary-row d-flex justify-content-between mb-3">
                            <span class="summary-label text-muted">Phí vận chuyển:</span>
                            <span class="summary-value fw-medium text-success">Miễn phí</span>
                        </div>
                        <hr class="summary-divider text-muted opacity-25">
                        <div class="summary-row total-row d-flex justify-content-between">
                            <span class="summary-label fw-bold">Tổng cộng:</span>
                            <span class="summary-value final-total fw-bold fs-5 text-primary" id="finalTotal">
                                <?php echo number_format($totalAmount, 0, ',', '.'); ?>đ
                            </span>
                        </div>
                        <?php if ($currentUser) { ?>
                            <button class="btn btn-primary w-90 mt-3 btn-checkout">Tiến hành thanh toán</button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-primary w-90 mt-3" onclick="showLoginToast()">Tiến hành thanh toán</button>
                        <?php } ?>
                        <div class="summary-policies mt-4 text-muted small">
                            <div class="policy-item mb-2"><i class="fa-solid fa-shield text-primary me-2"></i> Cam kết hàng
                                chính hãng</div>
                            <div class="policy-item mb-2"><i class="fa-solid fa-truck text-primary me-2"></i> Giao hàng
                                nhanh toàn quốc</div>
                            <div class="policy-item"><i class="fa-solid fa-box text-primary me-2"></i> Hỗ trợ đổi trả 7 ngày
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    <?php } ?>

</div>
<?php endblock() ?>
<?php startblock('js') ?>
<script>
    const checkAll = document.getElementById('checkAll');
    const itemchecks = document.querySelectorAll('.item-check');
    checkAll.addEventListener("change", function() {
        itemchecks.forEach(item => {
            item.checked = this.checked;
        })
        updateTotal();
    })
    itemchecks.forEach(item => {
        item.addEventListener("change", function() {
            let allchecked = true;
            itemchecks.forEach(i => {
                if (!i.checked) {
                    allchecked = false;
                }

            })
            checkAll.checked = allchecked;
            updateTotal();
        })
    })

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.item-check').forEach(item => {
            if (item.checked) {
                let price = Number(item.dataset.price);
                let row = item.closest('tr');
                let quantity = Number(row.querySelector('.cart-qty-input').value);
                total += price * quantity;
            }
        })
        document.getElementById('subtotalPrice').innerText = total.toLocaleString('vi-VN') + 'đ';
        document.getElementById('finalTotal').innerText = total.toLocaleString('vi-VN') + 'đ';
        // --- ĐOẠN CODE THÊM MỚI ĐỂ VÔ HIỆU HOÁ NÚT ---
        let btnCheckout = document.querySelector('.btn-checkout');
        if (btnCheckout) {
            if (total <= 0) {
                btnCheckout.disabled = true;  // Vô hiệu hoá nút
            } else {
                btnCheckout.disabled = false; // Bật lại nút
            }
        }
         // --- KẾT THÚC ĐOẠN CODE THÊM MỚI ---
    }
    // Xử lý nút cộng trừ số lượng
    document.querySelectorAll('.btn-qty-minus').forEach(button => {
        button.addEventListener('click', function() {
            let row = this.closest('tr');
            let input = row.querySelector('.cart-qty-input');
            let value = parseInt(input.value) - 1;
            if (value >= 1) {
                input.value = value;
                updateCartItemQuantity(row, value);
            }
        });
    });
    document.querySelectorAll('.btn-qty-plus').forEach(button => {
        button.addEventListener('click', function() {
            let row = this.closest('tr');
            let input = row.querySelector('.cart-qty-input');
            let value = parseInt(input.value) + 1;
            input.value = value;
            updateCartItemQuantity(row, value);
        });
    });

    function updateCartItemQuantity(row, quantity) {
        let checkbox = row.querySelector('.item-check');
        let maSanPham = checkbox.value;
        let formData = new FormData();
        formData.append('MaSanPham', maSanPham);
        formData.append('SoLuong', quantity);
        fetch('<?php echo url('CartController/update') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('.cart-count').innerText = data.totalItems;
                    updateTotal();
                }
            })

    }
    // Xử lý nút xoá sản phẩm
    document.querySelectorAll('.btn-remove-cart').forEach(button => {
        button.addEventListener('click', function() {
            let row = this.closest('tr');
            let checkbox = row.querySelector('.item-check');
            let maSanPham = checkbox.value;
            let formData = new FormData();
            formData.append('MaSanPham', maSanPham);
            fetch('<?php echo url('CartController/remove') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('.cart-count').innerText = data.totalItems;
                        row.remove();
                        updateTotal();
                    }
                });
        });
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