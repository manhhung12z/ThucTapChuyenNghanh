<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>

<?php startblock('content') ?>
<form action="<?php echo url('OrderController/handlecheckout') ?>" method="POST">
    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-md-8 order_info">
                <div class="order_info_left" style="background: #F8F9FA; border-radius: 10px; padding:20px;">
                    <h5 class="mb-3">Thông tin đơn hàng</h5>
                    <table class="table table-borderless align-middle">
                        <tbody>
                            <?php
                            $tongTienGocToanDon = 0;
                            $tongTienSauGiamToanDon = 0;
                            if (!empty($data['productDetails']) && is_array($data['productDetails'])) {
                                foreach ($data['productDetails'] as $item) {
                                    $giaGoc = $item['Gia'] ?? 0;
                                    $giaSauGiam = $item['GiaSauGiam'] ?? $giaGoc;
                                    $sl = $item['SoLuongMua'] ?? 1;

                                    $tongTienGocToanDon += $giaGoc * $sl;
                                    $tongTienSauGiamToanDon += $giaSauGiam * $sl;
                            ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo $item['HinhAnh'] ?>"
                                                alt="<?php echo $item['TenSanPham'] ?? 'Sản phẩm'; ?>"
                                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                        </td>
                                        <td><strong><?php echo $item['TenSanPham'] ?? 'Sản phẩm'; ?></strong>
                                            <br>
                                            <small class="text-muted">Mã SP: <?php echo $item['MaSanPham'] ?? ''; ?></small>
                                        </td>
                                        <td><?php echo number_format($giaSauGiam); ?>đ</td>
                                        <td><?php echo $sl; ?></td>
                                        <td>
                                            <?php echo number_format($giaSauGiam * $sl); ?>đ
                                        </td>
                                    </tr>
                                    <!-- Lưu dữ liệu ngầm của từng sản phẩm để gửi đi xử lý thanh toán -->
                                    <input type="hidden" name="MaSanPham[]" value="<?php echo $item['MaSanPham']; ?>">
                                    <input type="hidden" name="SoLuongMua[]" value="<?php echo $sl; ?>">
                                    <input type="hidden" name="DonGia[]" value="<?php echo $giaSauGiam; ?>">

                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="order_ship mt-3" style="background: #F8F9FA; border-radius: 10px; padding:20px;">
                    <h6 class="mt-3" style="display: flex; gap: 10px;"><i class="fa-regular fa-user"></i>Thông tin người
                        đặt</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">*Họ tên</label>
                            <input type="text" class="form-control" name="name" placeholder="Nhập họ tên của bạn"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">*Số điện thoại</label>
                            <input type="text" class="form-control" name="phone"
                                placeholder="Nhập số điện thoại của bạn" required>
                        </div>
                    </div>
                    <h6 class="mt-3" style="display: flex; gap: 10px;"><i class="fa-regular fa-address-book"></i>Thông
                        tin nhận hàng</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">*Địa chỉ</label>
                            <input type="text" class="form-control" name="address" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">*Tỉnh / Thành phố</label>
                            <select class="form-select" name="provinces" id="provinces" required>
                                <option value="">Chọn Tỉnh / Thành phố</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">*Quận / Huyện</label>
                            <select class="form-select" name="district" id="district" required>
                                <option value="">Chọn Quận / Huyện</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">*Phường / Xã</label>
                            <select class="form-select" name="ward" id="ward" required>
                                <option value="">Chọn Phường / Xã</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="order_bottom mt-4 mb-4" style="background: #F8F9FA; border-radius: 10px; padding:20px;">
                    <label class="payment-option">
                        <input type="radio" name="payment" value="momo" id="momo" required>
                        <div class="payment-content">
                            <img src="<?php echo asset('assets/web/img/momo.png') ?>" alt="MoMo" width="32">
                            <span>Ví điện tử MoMo</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment" value="cod" id="cod">
                        <div class="payment-content">
                            <i class="fa-solid fa-money-bill-wave text-success fs-4"></i>
                            <span>Tiền mặt khi nhận hàng (COD)</span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="col-md-4 order_detail"
                style="background: #F8F9FA; border-radius: 10px; padding:20px;align-self: flex-start;">
                <div class="order_detail_right">
                    <h6 class="mb-0">Chi tiết đơn hàng</h6>
                    <?php
                    $tienGiamGia = $tongTienGocToanDon - $tongTienSauGiamToanDon;
                    ?>
                    <div class="order_item" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span class="fw-bold">Tổng tiền:</span>
                        <span class="fw-bold"
                            style="font-size: 1.2rem;"><?php echo number_format($tongTienGocToanDon); ?>đ</span>
                    </div>

                    <div class="order_item" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span class="fw-bold">Giảm giá:</span>
                        <span class="fw-bold text-success"
                            style="font-size: 1.2rem;">-<?php echo number_format($tienGiamGia); ?>đ</span>
                    </div>
                    <div class="order_item" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span class="fw-bold">Phí vận chuyển:</span>
                        <span class="fw-bold text-primary" style="font-size: 1.2rem;">Miễn phí</span>
                    </div>
                    <hr>
                    <div class="order_total"
                        style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span class="fw-bold">Tổng thanh toán:</span>
                        <span class="fw-bold text-danger"
                            style="font-size: 1.5rem;"><?php echo number_format($tongTienSauGiamToanDon); ?>đ</span>
                        <input type="hidden" name="total" value="<?php echo $tongTienSauGiamToanDon; ?>">
                    </div>
                    <div class="d-grid mt-4 border-radius-50">
                        <button class="btn btn-primary" type="submit" name="order">Đặt hàng</button>
                    </div>
                    <p class="text-muted mt-2" style="font-size: 0.85rem;text-align: center;">Bằng việc tiến hành đặt
                        mua hàng, bạn đồng ý với Điều khoản dịch vụ của privia</p>
                </div>

            </div>
        </div>
    </div>
</form>
<?Php startblock('js') ?>
<script>
    //cách kết nối api để lấy dữ liệu bổ vào selectbox
    //cấu trúc tổng quát của ajax.
    /*
    $.ajax(
    {
    url:"API_URL" ĐƯỜNG DẪN XỬ LÝ AJAX.
    method:"GET|POST|PUT|DELETE",
    data:{
    dữ liệu gửi đi
    }
    datatype:"json",
    success: function(response)
    {
    console.log("Success:", response);
    }
    error: function (xhr, status, error) {
         console.log("Error:", error);
     },
 
     complete: function () {
         console.log("Request done");
     }
         fetch("url",{
         method:"GET"
         headers: {
         "Content-Type": "application/json",
         // Authorization: "Bearer token"
     },
 
     body: JSON.stringify({
         // dữ liệu gửi lên server
     })
 
 })
     .then(response => {
 
     // kiểm tra request thành công
     if (!response.ok) {
         throw new Error("Request failed");
     }
 
     // chuyển response sang json
     return response.json();
 
 })
 
 .then(data => {
 
     // xử lý dữ liệu
     console.log(data);
 
 })
 
 .catch(error => {
 
     // xử lý lỗi
     console.log(error);
 
 })
 
 
         })
 
    }
    )
    #có những cách viết hàm array arrow
    */
    const GHN_TOKEN = "8dad4f14-5c4c-11f1-8581-2e18172ecb31"; 

// Lấy danh sách Tỉnh/Thành từ GHN
fetch("https://online-gateway.ghn.vn/shiip/public-api/master-data/province", {
    method: "GET",
    headers: {
        "Content-Type": "application/json",
        "Token": GHN_TOKEN
    }
})
.then(response => {
    if (!response.ok) throw new Error("Không thể lấy danh sách tỉnh thành");
    return response.json();
})
.then(res => {
    if (res.code === 200 && res.data) {
        res.data.forEach(province => {
            $('#provinces').append(
                `<option value="${province.ProvinceID}">${province.ProvinceName}</option>`
            );
        });
    }
})
.catch(error => console.error('Lỗi tải tỉnh thành GHN:', error));

// Sự kiện khi chọn Tỉnh/Thành phố -> Gọi API lấy Quận/Huyện
$('#provinces').change(function() {
    const provinceId = $(this).val();
    // Reset các ô chọn cấp dưới
    $('#district').html('<option value="">Chọn Quận / Huyện</option>');
    $('#ward').html('<option value="">Chọn Phường / Xã</option>');

    if (!provinceId) {
        $('#district').prop('disabled', true);
        $('#ward').prop('disabled', true);
        return;
    }

    $('#district').prop('disabled', false);
    $('#ward').prop('disabled', true);

    // Gọi API GHN lấy Quận Huyện theo ProvinceID
    fetch("https://online-gateway.ghn.vn/shiip/public-api/master-data/district", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Token": GHN_TOKEN
        },
        body: JSON.stringify({ province_id: parseInt(provinceId) })
    })
    .then(response => response.json())
    .then(res => {
        if (res.code === 200 && res.data) {
            res.data.forEach(district => {
                $('#district').append(
                    `<option value="${district.DistrictID}">${district.DistrictName}</option>`
                );
            });
        }
    })
    .catch(error => console.error('Lỗi tải quận huyện GHN:', error));
});

// Gọi API lấy Phường/Xã
$('#district').change(function() {
    const districtId = $(this).val();
    
    // Reset ô chọn phường xã
    $('#ward').html('<option value="">Chọn Phường / Xã</option>');

    if (!districtId) {
        $('#ward').prop('disabled', true);
        return;
    }
    $('#ward').prop('disabled', false);

    // Gọi API GHN lấy Phường Xã theo DistrictID
    fetch("https://online-gateway.ghn.vn/shiip/public-api/master-data/ward", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Token": GHN_TOKEN
        },
        body: JSON.stringify({ district_id: parseInt(districtId) })
    })
    .then(response => response.json())
    .then(res => {
        if (res.code === 200 && res.data) {
            res.data.forEach(ward => {
                //  GHN dùng thuộc tính WardCode varchar thay vì int
                $('#ward').append(
                    `<option value="${ward.WardCode}">${ward.WardName}</option>`
                );
            });
        }
    })
    .catch(error => console.error('Lỗi tải phường xã GHN:', error));
});

//  Gom chuỗi địa chỉ 
// Giả sử id của form đặt hàng là #form-checkout, và ô nhập số nhà là #txt_diachicuthe
$('#form-checkout').submit(function(e) {
    const soNha = $('#txt_diachicuthe').val().trim().replace(/,/g, ' '); // Loại bỏ dấu phẩy
    const province = $('#provinces').val();
    const district = $('#district').val();
    const ward = $('#ward').val();

    const finalAddress = `${soNha}, ${ward}, ${district}, ${province}`;

    // Đút chuỗi này vào ô input ẩn name="DiaChiGiao" để gửi lên PHP xử lý
    $('#hdn_DiaChiGiao').val(finalAddress);
});
</script>
<?php if (Flash::has('error_sl')) { ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: <?php echo json_encode(Flash::get('error_sl')); ?>,
            showConfirmButton: false,
            timer: 3000, /* Đổi thành 3000 để hiển thị 3 giây cho khách dễ đọc */
            timerProgressBar: true
        });
    </script>
<?php } ?>
<?php endblock() ?>
<?php endblock() ?>