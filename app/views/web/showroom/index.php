```php
<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?php startblock('content') ?>

<style>
    .showroom-page {
        --main: #db7093;
        --dark: #2f2f2f;
        --muted: #777;
        --soft: #fff3f7;
        --white: #fff;
    }

    .showroom-hero {
        margin: 35px 0 45px;
        padding: 65px 30px;
        border-radius: 28px;
        text-align: center;
        background: linear-gradient(135deg, #fff1f6, #ffffff, #ffe4ee);
        box-shadow: 0 12px 35px rgba(219, 112, 147, 0.15);
    }

    .showroom-hero span {
        display: inline-block;
        padding: 7px 18px;
        border-radius: 999px;
        background: #fff;
        color: var(--main);
        font-weight: 700;
        margin-bottom: 15px;
    }

    .showroom-hero h2 {
        font-size: 2.4rem;
        font-weight: 800;
        color: var(--dark);
    }

    .showroom-hero p {
        max-width: 760px;
        margin: 12px auto 0;
        color: var(--muted);
        line-height: 1.8;
    }

    .showroom-wrapper {
        background: #fff;
        border-radius: 28px;
        padding: 24px;
        box-shadow: 0 12px 35px rgba(0,0,0,0.08);
        margin-bottom: 60px;
    }

    .store-list {
        height: 650px;
        overflow-y: auto;
        padding-right: 6px;
    }

    .store-heading {
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 18px;
        color: var(--dark);
    }

    .store-heading i {
        color: var(--main);
        margin-right: 8px;
    }

    .store-item {
        border: 1px solid #f1d6e0;
        border-radius: 22px;
        padding: 20px;
        margin-bottom: 16px;
        cursor: pointer;
        background: #fff;
        transition: 0.3s;
    }

    .store-item:hover,
    .store-item.active {
        background: var(--soft);
        border-color: var(--main);
        box-shadow: 0 10px 25px rgba(219, 112, 147, 0.18);
        transform: translateY(-4px);
    }

    .store-badge {
        display: inline-block;
        padding: 5px 13px;
        border-radius: 999px;
        background: #fff;
        color: var(--main);
        border: 1px solid #efbfd0;
        font-size: 0.78rem;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .store-item h5 {
        font-size: 1.03rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 13px;
    }

    .store-info {
        display: flex;
        gap: 10px;
        color: var(--muted);
        font-size: 0.92rem;
        margin-bottom: 9px;
        line-height: 1.5;
    }

    .store-info i {
        color: var(--main);
        min-width: 16px;
        margin-top: 4px;
    }

    .store-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 15px;
    }

    .btn-map {
        padding: 8px 17px;
        border-radius: 999px;
        background: var(--main);
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .btn-map:hover {
        background: #c85f82;
        color: #fff;
    }

    .btn-call {
        padding: 8px 17px;
        border-radius: 999px;
        background: #fff;
        color: var(--main);
        border: 1px solid var(--main);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .btn-call:hover {
        background: var(--main);
        color: #fff;
    }

    .map-box {
        height: 650px;
        border-radius: 24px;
        overflow: hidden;
        background: #f7f7f7;
    }

    .map-box iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }

    .service-section {
        background: #fafafa;
        border-radius: 28px;
        padding: 55px 30px;
        margin-bottom: 55px;
    }

    .section-title {
        text-align: center;
        margin-bottom: 35px;
    }

    .section-title h3 {
        font-weight: 800;
        color: var(--dark);
    }

    .section-title p {
        color: var(--muted);
    }

    .service-card {
        background: #fff;
        border-radius: 24px;
        padding: 32px 24px;
        height: 100%;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        transition: 0.3s;
    }

    .service-card:hover {
        transform: translateY(-5px);
    }

    .service-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--soft);
        color: var(--main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 18px;
    }

    .service-card h5 {
        font-weight: 800;
        margin-bottom: 12px;
    }

    .service-card p {
        color: var(--muted);
        font-size: 0.92rem;
        line-height: 1.7;
        margin-bottom: 0;
    }

    .contact-panel {
        background: #fff;
        border-radius: 28px;
        padding: 35px;
        margin-bottom: 55px;
        box-shadow: 0 10px 28px rgba(0,0,0,0.07);
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 13px;
        color: var(--muted);
        margin-bottom: 15px;
    }

    .contact-item i {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--soft);
        color: var(--main);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 991px) {
        .store-list {
            height: auto;
            margin-bottom: 20px;
        }

        .map-box {
            height: 460px;
        }
    }

    @media (max-width: 768px) {
        .showroom-hero {
            padding: 45px 20px;
        }

        .showroom-hero h2 {
            font-size: 1.8rem;
        }

        .map-box {
            height: 360px;
        }
    }
</style>

<div class="container showroom-page">

    <div class="showroom-hero">
        <span>Privia Store System</span>
        <h2>Hệ Thống Showroom Privia</h2>
        <p>
            Tìm showroom Privia gần bạn nhất để trải nghiệm sản phẩm chính hãng,
            nhận tư vấn chăm sóc da và được hỗ trợ mua sắm trực tiếp tại cửa hàng.
        </p>
    </div>

    <div class="showroom-wrapper">
        <div class="row g-4">

            <div class="col-lg-4">
                <div class="store-heading">
                    <i class="fa-solid fa-location-dot"></i>
                    Danh sách cửa hàng
                </div>

                <div class="store-list">

                    <div class="store-item active" onclick="changeMap(this, '120 Đường Lĩnh Nam, Vĩnh Hưng, Hà Nội')">
                        <span class="store-badge">Đang mở cửa</span>
                        <h5>Privia Lĩnh Nam - Hà Nội</h5>

                        <div class="store-info">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>120 Đường Lĩnh Nam, Vĩnh Hưng, Hà Nội</span>
                        </div>

                        <div class="store-info">
                            <i class="fa-solid fa-phone"></i>
                            <span>0988 123 456</span>
                        </div>

                        <div class="store-info">
                            <i class="fa-solid fa-clock"></i>
                            <span>08:00 - 21:00 hằng ngày</span>
                        </div>

                        <div class="store-actions">
                            <a href="https://www.google.com/maps?q=120+Đường+Lĩnh+Nam,+Vĩnh+Hưng,+Hà+Nội"
                               target="_blank"
                               class="btn-map"
                               onclick="event.stopPropagation();">
                                Chỉ đường
                            </a>

                            <a href="tel:0988123456"
                               class="btn-call"
                               onclick="event.stopPropagation();">
                                Gọi ngay
                            </a>
                        </div>
                    </div>

                    <div class="store-item" onclick="changeMap(this, '45 Nguyễn Trãi, Quận 1, Thành phố Hồ Chí Minh')">
                        <span class="store-badge">Đang mở cửa</span>
                        <h5>Privia Nguyễn Trãi - TP. HCM</h5>

                        <div class="store-info">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>45 Nguyễn Trãi, Quận 1, TP. Hồ Chí Minh</span>
                        </div>

                        <div class="store-info">
                            <i class="fa-solid fa-phone"></i>
                            <span>0977 456 789</span>
                        </div>

                        <div class="store-info">
                            <i class="fa-solid fa-clock"></i>
                            <span>08:30 - 21:30 hằng ngày</span>
                        </div>

                        <div class="store-actions">
                            <a href="https://www.google.com/maps?q=45+Nguyễn+Trãi,+Quận+1,+TP+Hồ+Chí+Minh"
                               target="_blank"
                               class="btn-map"
                               onclick="event.stopPropagation();">
                                Chỉ đường
                            </a>

                            <a href="tel:0977456789"
                               class="btn-call"
                               onclick="event.stopPropagation();">
                                Gọi ngay
                            </a>
                        </div>
                    </div>

                    <div class="store-item" onclick="changeMap(this, '88 Nguyễn Văn Linh, Hải Châu, Đà Nẵng')">
                        <span class="store-badge">Đang mở cửa</span>
                        <h5>Privia Hải Châu - Đà Nẵng</h5>

                        <div class="store-info">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>88 Nguyễn Văn Linh, Hải Châu, Đà Nẵng</span>
                        </div>

                        <div class="store-info">
                            <i class="fa-solid fa-phone"></i>
                            <span>0966 789 123</span>
                        </div>

                        <div class="store-info">
                            <i class="fa-solid fa-clock"></i>
                            <span>08:00 - 20:30 hằng ngày</span>
                        </div>

                        <div class="store-actions">
                            <a href="https://www.google.com/maps?q=88+Nguyễn+Văn+Linh,+Hải+Châu,+Đà+Nẵng"
                               target="_blank"
                               class="btn-map"
                               onclick="event.stopPropagation();">
                                Chỉ đường
                            </a>

                            <a href="tel:0966789123"
                               class="btn-call"
                               onclick="event.stopPropagation();">
                                Gọi ngay
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-8">
                <div class="map-box">
                    <iframe
                        id="showroomMap"
                        src="https://www.google.com/maps?q=120+Đường+Lĩnh+Nam,+Vĩnh+Hưng,+Hà+Nội&output=embed"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                </div>
            </div>

        </div>
    </div>

    <div class="service-section">
        <div class="section-title">
            <h3>Dịch Vụ Tại Showroom</h3>
            <p>Privia mang đến trải nghiệm chăm sóc khách hàng tận tâm và chuyên nghiệp.</p>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <h5>Tư Vấn Da Cá Nhân</h5>
                    <p>Nhân viên hỗ trợ phân tích tình trạng da và gợi ý sản phẩm phù hợp với từng khách hàng.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fa-solid fa-spray-can-sparkles"></i>
                    </div>
                    <h5>Trải Nghiệm Sản Phẩm</h5>
                    <p>Khách hàng có thể dùng thử sản phẩm trực tiếp trước khi lựa chọn mua hàng.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fa-solid fa-gift"></i>
                    </div>
                    <h5>Ưu Đãi Tại Cửa Hàng</h5>
                    <p>Nhiều chương trình khuyến mãi, quà tặng và chính sách chăm sóc riêng cho khách hàng.</p>
                </div>
            </div>

        </div>
    </div>

    <div class="contact-panel">
        <h3 class="fw-bold mb-4">Liên Hệ Showroom</h3>

        <div class="row gy-3">
            <div class="col-md-4">
                <div class="contact-item">
                    <i class="fa-solid fa-phone"></i>
                    <span>Hotline: 1900 888 999</span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-item">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Email: support@privia.vn</span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-item">
                    <i class="fa-solid fa-clock"></i>
                    <span>Hỗ trợ: 08:00 - 22:00 hằng ngày</span>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function changeMap(element, address) {
        document.getElementById("showroomMap").src =
            "https://www.google.com/maps?q=" +
            encodeURIComponent(address) +
            "&output=embed";

        document.querySelectorAll(".store-item").forEach(function(item) {
            item.classList.remove("active");
        });

        element.classList.add("active");
    }
</script>

<?php endblock() ?>
 

