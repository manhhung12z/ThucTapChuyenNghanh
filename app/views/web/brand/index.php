<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?php startblock('content') ?>

<style>
    .brand-hero-container {
        width: 100%;
        margin-bottom: 10px;
    }
    .brand-hero-img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .story-block {
        padding: 50px 0;
    }
    .quote-box {
        border-left: 4px solid #ffb6c1; /* Màu hồng pastel hợp với ngành mỹ phẩm */
        padding-left: 20px;
        font-style: italic;
        background-color: #fbfbfb;
        padding: 20px;
        border-radius: 0 8px 8px 0;
    }
    .tech-card {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        height: 100%;
        transition: transform 0.3s;
    }
    .tech-card:hover {
        transform: translateY(-5px);
    }
    .tech-icon {
        font-size: 2.5rem;
        color: #db7093;
        margin-bottom: 15px;
    }
</style>

<div class="container mt-3">
    <div class="story-block">
        <div class="row align-items-center gy-4">
            <div class="col-md-6">
                <h3 class="fw-bold mb-3" style="color: #333;">Câu Chuyện Thương Hiệu</h3>
                <p class="text-muted" style="line-height: 1.8;">
                    Được thành lập tại "vương quốc làm đẹp" Hàn Quốc, <strong>Privia</strong> (hay Privia U) chọn cho mình một lối đi khác biệt. Không chạy theo những xu hướng truyền thông rầm rộ, Privia âm thầm định vị mình là một thương hiệu mỹ phẩm chức năng chăm sóc chuyên sâu (Functional Skincare) được các chuyên gia da liễu và viện thẩm mỹ khuyên dùng. Với cột mốc bứt phá từ năm 2018, thương hiệu mang hơi thở đương đại chuẩn New York, kết hợp hoàn hảo giữa công nghệ hàng đầu và thiết kế sang trọng.
                </p>
                <div class="quote-box mt-4">
                    <h5>Sứ mệnh từ cái tên "Privia"</h5>
                    <p class="mb-0 text-muted">
                        Tên gọi <strong>Privia</strong> là sự kết hợp hoàn hảo giữa <strong>"Privilege" (Đặc quyền)</strong> và <strong>"You" (Bạn)</strong>. Chúng tôi tin rằng một làn da rạng rỡ, khỏe mạnh từ gốc không phải là sự xa xỉ bẩm sinh, mà là đặc quyền mà bất kỳ ai cũng có thể chạm tới nếu biết yêu thương bản thân đúng cách.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <img src="<?php echo asset('assets/web/img/brand2.png') ?>" class="img-fluid rounded w-100 shadow-sm" alt="Privia Brand Story">
            </div>
        </div>
    </div>

    <hr>

    <div class="story-block bg-light px-4 rounded">
        <h3 class="text-center fw-bold mb-5">Triết Lý Làm Đẹp Tạo Nên Khác Biệt</h3>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="tech-card text-center">
                    <div class="tech-icon">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <h5 class="fw-bold">Công Nghệ Lên Men</h5>
                    <p class="text-muted small mb-0">Các dưỡng chất tự nhiên được bẻ gãy thành phân tử siêu nhỏ qua quá trình lên men sinh học, giúp thẩm thấu sâu vào tế bào da mà hoàn toàn không gây kích ứng.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tech-card text-center">
                    <div class="tech-icon">
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                    <h5 class="fw-bold">Thảo Dược Phương Đông</h5>
                    <p class="text-muted small mb-0">Chắt lọc tinh hoa từ mầm gạo, hoa sen trắng và các bài thuốc y học cổ truyền giúp nuôi dưỡng và tái tạo sắc tố da tự nhiên, an toàn lâu dài.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tech-card text-center">
                    <div class="tech-icon">
                        <i class="fa-solid fa-spa"></i>
                    </div>
                    <h5 class="fw-bold">Chuẩn Spa Tại Nhà</h5>
                    <p class="text-muted small mb-0">Kế thừa các công thức chuyên dụng từ phòng liệu trình chuyên nghiệp, Privia đóng gói giải pháp nuôi dưỡng chuyên sâu tối ưu chi phí ngay tại nhà.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="commitments-section mt-5 mb-5">
        <div class="row text-center gy-4">
            <div class="col-6 col-lg-3">
                <div class="commitment-card">
                    <div class="commitment-icon bg-light-blue">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h5 class="fw-bold mt-3 mb-2" style="font-size: 1rem;">100% Chính Hãng Privia</h5>
                    <p class="text-muted small mb-0">Cam kết nhập khẩu chính ngạch từ Hàn Quốc, hoàn tiền 200% nếu phát hiện hàng giả.</p>
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
                    <p class="text-muted small mb-0">Đội ngũ chuyên viên hiểu rõ về làn da sẵn sàng hỗ trợ bạn 24/7.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endblock() ?>