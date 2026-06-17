<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?php startblock('css') ?>
<style>
    .policy-banner {
        text-align: center;
        padding: 70px 20px 90px;
        background: linear-gradient(135deg, #fff5f8, #ffe3ec);
    }

    .policy-banner h1 {
        font-size: 46px;
        color: #d6336c;
        font-family: serif;
        margin-bottom: 12px;
    }

    .policy-banner p {
        max-width: 700px;
        margin: 0 auto;
        color: #555;
        font-size: 17px;
        line-height: 1.7;
    }

    .policy-page {
        width: 88%;
        max-width: 1100px;
        margin: -45px auto 60px;
        background: #fff;
        border-radius: 26px;
        padding: 20px 35px;
        box-shadow: 0 12px 35px rgba(214, 51, 108, 0.15);
    }

    .policy-item {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 25px;
        padding: 32px 0;
        border-bottom: 1px solid #ffe0e9;
        align-items: flex-start;
    }

    .policy-item:last-child {
        border-bottom: none;
    }

    .policy-icon {
        width: 95px;
        height: 95px;
        border-radius: 50%;
        background: #fff0f5;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(255, 92, 138, 0.18);
    }

    .policy-icon i {
        font-size: 42px;
        color: #ff5c8a;
    }

    .policy-content h3 {
        font-size: 22px;
        color: #d6336c;
        margin-bottom: 12px;
        font-family: serif;
    }

    .policy-content p {
        color: #444;
        line-height: 1.7;
        margin-bottom: 10px;
    }

    .policy-content ul {
        padding-left: 20px;
        margin-bottom: 0;
    }

    .policy-content li {
        color: #555;
        line-height: 1.7;
        margin-bottom: 6px;
    }

    @media (max-width: 768px) {
        .policy-banner {
            padding: 45px 18px 70px;
        }

        .policy-banner h1 {
            font-size: 34px;
        }

        .policy-banner p {
            font-size: 15px;
        }

        .policy-page {
            width: 94%;
            padding: 10px 20px;
            margin-top: -35px;
            border-radius: 20px;
        }

        .policy-item {
            grid-template-columns: 1fr;
            gap: 15px;
            text-align: center;
            padding: 28px 0;
        }

        .policy-icon {
            margin: 0 auto;
            width: 82px;
            height: 82px;
        }

        .policy-icon i {
            font-size: 34px;
        }

        .policy-content h3 {
            font-size: 20px;
        }

        .policy-content ul {
            text-align: left;
        }
</style>
<?php endblock() ?>
<?php startblock('content') ?>
<section class="policy-banner">
    <h1>Chính sách Privia</h1>
    <p>Privia cam kết mang đến trải nghiệm mua sắm mỹ phẩm an toàn, minh bạch và chuyên nghiệp.</p>
</section>

<section class="policy-page">

    <div class="policy-item">
        <div class="policy-icon">
            <i class="fa-solid fa-shield-heart"></i>
        </div>

        <div class="policy-content">
            <h3>1. Chính sách chất lượng sản phẩm</h3>
            <p>Privia cam kết cung cấp sản phẩm mỹ phẩm chính hãng, có nguồn gốc rõ ràng và đảm bảo chất lượng khi đến tay khách hàng.</p>
            <ul>
                <li>Sản phẩm có đầy đủ thông tin thương hiệu, thành phần và hạn sử dụng.</li>
                <li>Không kinh doanh hàng giả, hàng nhái, hàng kém chất lượng.</li>
                <li>Hỗ trợ kiểm tra và xử lý nếu sản phẩm có vấn đề về chất lượng.</li>
            </ul>
        </div>
    </div>

    <div class="policy-item">
        <div class="policy-icon">
            <i class="fa-solid fa-truck-fast"></i>
        </div>

        <div class="policy-content">
            <h3>2. Chính sách vận chuyển</h3>
            <p>Privia hỗ trợ giao hàng toàn quốc thông qua các đơn vị vận chuyển uy tín.</p>
            <ul>
                <li>Thời gian giao hàng nội thành: 1 - 2 ngày làm việc.</li>
                <li>Thời gian giao hàng tỉnh/thành khác: 2 - 5 ngày làm việc.</li>
                <li>Miễn phí vận chuyển cho đơn hàng từ 500.000đ.</li>
                <li>Đơn hàng dưới 500.000đ áp dụng phí vận chuyển theo đơn vị giao hàng.</li>
            </ul>
        </div>
    </div>

    <div class="policy-item">
        <div class="policy-icon">
            <i class="fa-solid fa-credit-card"></i>
        </div>

        <div class="policy-content">
            <h3>3. Chính sách thanh toán</h3>
            <p>Privia hỗ trợ nhiều phương thức thanh toán để khách hàng dễ dàng lựa chọn.</p>
            <ul>
                <li>Thanh toán khi nhận hàng bằng hình thức COD.</li>
                <li>Thanh toán trực tuyến qua ví điện tử hoặc cổng thanh toán.</li>
                <li>Thông tin thanh toán của khách hàng được bảo mật.</li>
                <li>Đơn hàng chỉ được xử lý sau khi hệ thống xác nhận thanh toán thành công đối với hình thức online.</li>
            </ul>
        </div>
    </div>

    <div class="policy-item">
        <div class="policy-icon">
            <i class="fa-solid fa-box-open"></i>
        </div>

        <div class="policy-content">
            <h3>4. Chính sách đổi trả</h3>
            <p>Privia hỗ trợ đổi trả sản phẩm trong trường hợp sản phẩm bị lỗi, hư hỏng hoặc giao sai so với đơn hàng.</p>
            <ul>
                <li>Thời gian hỗ trợ đổi trả: trong vòng 7 ngày kể từ khi nhận hàng.</li>
                <li>Sản phẩm còn nguyên tem, nhãn, bao bì và chưa qua sử dụng.</li>
                <li>Không áp dụng đổi trả với sản phẩm đã mở nắp hoặc hư hỏng do lỗi từ khách hàng.</li>
            </ul>
        </div>
    </div>

    <div class="policy-item">
        <div class="policy-icon">
            <i class="fa-solid fa-lock"></i>
        </div>

        <div class="policy-content">
            <h3>5. Chính sách bảo mật thông tin</h3>
            <p>Privia tôn trọng và cam kết bảo vệ thông tin cá nhân của khách hàng trong quá trình mua sắm.</p>
            <ul>
                <li>Không chia sẻ thông tin khách hàng cho bên thứ ba nếu không có sự đồng ý.</li>
                <li>Thông tin cá nhân chỉ được sử dụng để xử lý đơn hàng và chăm sóc khách hàng.</li>
                <li>Các thông tin đăng nhập và thanh toán được bảo vệ an toàn.</li>
            </ul>
        </div>
    </div>

    <div class="policy-item">
        <div class="policy-icon">
            <i class="fa-solid fa-headset"></i>
        </div>

        <div class="policy-content">
            <h3>6. Chính sách hỗ trợ khách hàng</h3>
            <p>Đội ngũ Privia luôn sẵn sàng hỗ trợ khách hàng trong quá trình tìm hiểu, đặt hàng và sử dụng sản phẩm.</p>
            <ul>
                <li>Hỗ trợ tư vấn sản phẩm phù hợp với nhu cầu chăm sóc da.</li>
                <li>Tiếp nhận phản hồi, khiếu nại và xử lý trong thời gian sớm nhất.</li>
                <li>Hỗ trợ qua hotline, email hoặc hệ thống liên hệ trên website.</li>
            </ul>
        </div>
    </div>

</section>
<?php endblock() ?>