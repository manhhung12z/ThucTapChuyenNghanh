<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?php require_once(_DIR_ROOT . '/app/Controllers/Web/AuthController.php') ?>
<?php
$currentUser = auth::getUser('user');
?>
<?php startblock('css') ?>
<style>
    .order-history {
        padding: 30px 0;
    }

    .order-title {
        font-weight: 700;
        margin-bottom: 20px;
    }

    .order-tabs {
        display: flex;
        gap: 15px;
        border-bottom: 1px solid #eee;
        margin-bottom: 30px;
    }

    .order-tabs a {
        text-decoration: none;
        color: #555;
        padding-bottom: 14px;
        font-size: 15px;
    }

    .order-tabs a.active {
        color: #e94b7b;
        border-bottom: 2px solid #e94b7b;
    }

    .order-card {
        background: #FEFCFD;
        border: 1px solid #eee;
        border-radius: 14px;
        margin-bottom: 18px;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .order-head {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        padding: 14px 20px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    .order-head span {
        color: #777;
    }

    .order-status {
        color: #159947;
        font-weight: 600;
        text-align: center;
    }

    .order-head strong {
        margin-left: 8px;
    }

    .order-body {
        display: grid;
        grid-template-columns: 1.5fr 1fr 220px;
        align-items: center;
    }

    .product-info {
        display: flex;
        gap: 18px;
        padding: 22px 24px;
    }

    .product-info img {
        width: 82px;
        height: 82px;
        object-fit: contain;
        border-radius: 10px;
        background: #fff3f7;
    }

    .product-info h5 {
        font-size: 16px;
        margin-bottom: 6px;
    }

    .product-info p {
        margin: 0;
        color: #555;
    }

    .product-info strong {
        color: #e94b7b;
        font-size: 16px;
    }

    .total-box {
        border-left: 1px solid #eee;
        border-right: 1px solid #eee;
        padding: 22px;
    }

    .total-box small {
        color: #444;
    }

    .total-box h4 {
        color: #e94b7b;
        margin: 6px 0;
        font-weight: 700;
    }

    .total-box p {
        margin: 0;
        color: #777;
    }

    .action-box {
        padding: 22px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn-buy,
    .btn-review {
        display: block;
        text-align: center;
        padding: 9px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-buy {
        border: 1px solid #aaa;
        color: #222;
    }

    .btn-review {
        border: 1px solid #e94b7b;
        color: #e94b7b;
    }

    .btn-buy:hover,
    .btn-review:hover {
        background: #fff1f6;
    }

    @media(max-width:992px) {
        .order-tabs {
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 5px;
            border-bottom: none;
        }

        .order-head {
            grid-template-columns: 1fr;
            gap: 5px;
        }

        .order-status {
            text-align: left;
        }

        .order-body {
            grid-template-columns: 1fr;
        }

        .total-box {
            border-left: none;
            border-right: none;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .action-box {
            flex-direction: row;
        }

        .btn-buy,
        .btn-review {
            flex: 1;
        }
    }

    @media (max-width: 576px) {
        .order-history {
            padding: 20px 0;
        }

        .order-title {
            font-size: 22px;
        }

        .order-head {
            padding: 12px 14px;
            font-size: 13px;
        }

        .product-info img {
            width: 70px;
            height: 70px;
        }

        .product-info h5 {
            font-size: 14px;
        }

        .total-box {
            padding: 16px;
        }

        .action-box {
            padding: 14px;
            gap: 8px;
        }
    }

    .review-modal {
        border: none;
        border-radius: 22px;
        overflow: hidden;
    }

    .review-product-box {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #fff6fa;
        padding: 14px;
        border-radius: 16px;
        margin-bottom: 20px;
    }

    .review-product-box img {
        width: 75px;
        height: 75px;
        object-fit: contain;
        border-radius: 12px;
        background: white;
    }

    .review-product-box h6 {
        margin: 0;
        font-weight: 600;
    }

    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 8px;
        margin: 15px 0 22px;
    }

    .rating-stars input {
        display: none;
    }

    .rating-stars label {
        font-size: 38px;
        color: #ddd;
        cursor: pointer;
        transition: 0.2s;
    }

    .review-textarea {
        border-radius: 14px;
        resize: none;
    }

    .review-textarea:focus {
        border-color: #e94b7b;
        box-shadow: 0 0 0 0.15rem rgba(233, 75, 123, 0.15);
    }

    .btn-submit-review {
        border: none;
        background: #e94b7b;
        color: white;
        padding: 9px 22px;
        border-radius: 10px;
        font-weight: 600;
    }

    .rating-stars input:checked~label,
    .rating-stars label:hover,
    .rating-stars label:hover~label {
        color: #EACB19;
        transform: scale(1.08);
    }

    .btn-cancel {
        display: inline-block;
        padding: 4px 10px;
        margin-top: 6px;
        border-radius: 6px;
        font-size: 13px;
        text-decoration: none;
        background-color: #fff;
        color: #dc3545;
        border: 1px solid #dc3545;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background-color: #dc3545;
        color: #fff;
    }
</style>
<?php endblock() ?>
<?php startblock('content') ?>
<?Php
$groupedOrders = [];
foreach ($orders ?? [] as $row) {
    $ma = $row['MaDonHang'];
    if (!isset($groupedOrders[$ma])) {
        $groupedOrders[$ma] = [
            'MaDonHang' => $row['MaDonHang'],
            'NgayDat' => $row['NgayDat'],
            'TrangThai' => $row['TrangThai'],
            'PhuongThuc' => $row['PhuongThuc'],
            'SanPham' => [],
        ];
    }
    $groupedOrders[$ma]['SanPham'][] = $row;
}
?>
<div class="container">
    <div class="order-history">
        <h3 class="order-title">Đơn hàng của tôi</h3>
        <div class="order-tabs">
            <a class="active" href="#">Tất cả</a>
        </div>
        <?php foreach ($groupedOrders as $group) { ?>
            <div class="order-card">
                <div class="order-head">
                    <div>
                        <span>Mã đơn hàng:</span>
                        <strong><?php echo $group['MaDonHang']; ?></strong>
                        <span class="line">|</span>
                        <span>Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($group['NgayDat'])); ?></span>
                    </div>

                    <div class="order-status">
                        <div><?php echo $group['TrangThai']; ?></div>
                        <?php if ($group['TrangThai'] === 'Chờ lấy hàng') { ?>
                            <a href="<?php echo url('OrderController/cancel?id=' . $group['MaDonHang']) ?>"
                                class="btn-cancel"
                                onclick="return confirmCancel(event,this)">
                                Hủy đơn hàng
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <?php foreach ($group['SanPham'] as $order) { ?>
                    <div class="order-body">
                        <div class="product-info">
                            <img src="<?php echo $order['HinhAnh']; ?>" alt="">
                            <div>
                                <h5><?php echo $order['TenSanPham']; ?></h5>
                                <p>x <?php echo $order['SoLuong']; ?></p>
                                <strong><?php echo number_format($order['DonGia']); ?>đ</strong>
                            </div>
                        </div>
                        <div class="total-box">
                            <small>Tổng tiền:</small>
                            <h4><?php echo number_format($order['DonGia']); ?>đ</h4>
                            <p><?php if ($order['PhuongThuc']) {
                                    echo $order['PhuongThuc'];
                                } ?></p>
                        </div>
                        <div class="action-box">
                            <a href="<?php echo url("ProductController/index?MaSanPham=" . $order['MaSanPham'] . " ") ?>" class="btn-buy">Mua lại</a>
                            <button type="button" class="btn-review" data-bs-toggle="modal" data-bs-target="#reviewModel" data-masp="<?php echo $order['MaSanPham']; ?>"
                                data-name="<?php echo htmlspecialchars($order['TenSanPham']); ?>"
                                data-img="<?php echo $order['HinhAnh']; ?>">
                                ♡ Đánh giá sản phẩm
                            </button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <div class="modal fade" id="reviewModel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content review-modal">
                <form method="POST" action="<?php echo url('ReviewController/store') ?>">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Đánh giá sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="MaSanPham" id="modalMaSP">
                        <div class="review-product-box">
                            <img id="modalProductImg" src="">
                            <h6 id="modalProductName"></h6>
                        </div>
                        <div class="rating-stars">
                            <input type="radio" name="SoSao" value="5" id="star5" required>
                            <label for="star5">★</label>

                            <input type="radio" name="SoSao" value="4" id="star4">
                            <label for="star4">★</label>

                            <input type="radio" name="SoSao" value="3" id="star3">
                            <label for="star3">★</label>

                            <input type="radio" name="SoSao" value="2" id="star2">
                            <label for="star2">★</label>

                            <input type="radio" name="SoSao" value="1" id="star1">
                            <label for="star1">★</label>
                        </div>
                        <textarea name="NoiDung"
                            class="form-control review-textarea"
                            rows="4"
                            placeholder="Hãy chia sẻ cảm nhận của bạn..."
                            required></textarea>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Đóng
                        </button>

                        <button type="submit" class="btn-submit-review">
                            Gửi đánh giá
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endblock() ?>
<?php startblock('js') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reviewButtons = document.querySelectorAll('.btn-review');
        reviewButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-masp');
                const productName = this.getAttribute('data-name');
                const productImg = this.getAttribute('data-img');

                document.getElementById('modalMaSP').value = productId;
                document.getElementById('modalProductName').innerText = productName;
                document.getElementById('modalProductImg').src = productImg;
            });
        });
    });
</script>
<?php if (Flash::has('success_review')) { ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: <?php echo json_encode(Flash::get('success_review')); ?>,
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true
        });
    </script>
<?php } ?>
<?php if (Flash::has('error_review')) { ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: <?php echo json_encode(Flash::get('error_review')); ?>,
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true
        });
    </script>
<?php } ?>
<?php if (Flash::has('error_sl_product')) { ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: <?php echo json_encode(Flash::get('error_sl_product')); ?>,
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true
        });
    </script>
<?php } ?>
<?php if (Flash::has('success_sl_product')) { ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: <?php echo json_encode(Flash::get('success_sl_product')); ?>,
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true
        });
    </script>
<?php } ?>
<?php if (isset($success)) { ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: <?php echo json_encode($success); ?>,
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true
        });
    </script>
<?php } ?>
<?php if (isset($error)) { ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: <?php echo json_encode($error); ?>,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    </script>
<?php } ?>
<script>
function confirmCancel(event, element) {
    event.preventDefault();

    Swal.fire({
        title: 'Hủy đơn hàng?',
        text: 'Bạn có chắc chắn muốn hủy đơn hàng này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Có, hủy đơn',
        cancelButtonText: 'Không',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = element.href;
        }
    });

    return false;
}
</script>
<script>
// Thay thế URL hiện tại trên thanh địa chỉ thành URL trang lịch sử đơn hàng
if (window.history.replaceState) {
    window.history.replaceState(null, null, "<?php echo url('OrderController/history'); ?>");
}
</script> cả thêm đoạn này ở trong js cuối trang history
<?php endblock() ?>