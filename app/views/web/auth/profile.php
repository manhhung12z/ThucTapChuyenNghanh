<?php require_once(_DIR_ROOT . '/app/views/web/layouts/index.php') ?>
<?Php startblock('cs') ?>
<style>
    .profile-page {
        padding: 60px 20px;
        background: #fafafa;
    }

    .profile-card {
        border-radius: 24px;
        display: grid;
        grid-template-columns: 360px 1fr;
        margin: auto;
        overflow: hidden;
        max-width: 1100px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
    }

    .profile-left {
        background: linear-gradient(135deg, #ffd6e8, #e8d7ff, #d7ecff);
        padding: 70px 30px;
        text-align: center;
    }

    .profile-avatar {
        width: 180px;
        height: 180px;
        margin: auto;
        border-radius: 50%;
        background: #fff;
        color: #ec4f91;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 90px;
        box-shadow: 0 8px 25px rgba(255, 105, 180, 0.25);
    }

    .profile-left h3 {
        margin-top: 28px;
        font-size: 28px;
        font-weight: 700;
    }

    .profile-left p {
        color: #ec4f91;
        font-size: 18px;
    }

    .profile-right {
        padding: 55px 65px;
    }

    .profile-right h2 {
        font-size: 32px;
        font-weight: 700;
    }

    .title-line {
        width: 60px;
        height: 4px;
        background: #ec4f91;
        border-radius: 10px;
        margin: 18px 0 35px;
    }

    .info-row {
        display: grid;
        grid-template-columns: 35px 180px 1fr;
        align-items: center;
        padding: 18px 0;
        border-bottom: 1px solid #eee;
        font-size: 17px;
    }

    .info-row i {
        color: #ec4f91;
        font-size: 20px;
    }

    .info-row span {
        color: #333;
    }

    .profile-actions {
        margin-top: 40px;
        display: flex;
        gap: 25px;
    }

    .profile-actions a,.profile-actions button {
        text-decoration: none;
        padding: 15px 32px;
        border-radius: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: none;
    }

    .btn-update {
        background: linear-gradient(135deg, #f76cae, #ff9ac8);
        color: #fff;
    }

    .info-row input {
        width: 100%;
        border: none;
        outline:none;
        background: #f8f8f8;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 16px;
        color: #333;
    }

    .info-row input:focus {
        background: #fff;
        box-shadow: 0 0 0 2px #f7a6c9;
    }

    .btn-password {
        background: #f3f3f3;
        color: #222;
    }
</style>
<?php endblock() ?>
<?php startblock('content') ?>
<form class="profile-form" action="<?php echo url('AuthController/updateprofile') ?>" method="POST">
    <?php foreach ($profiles as $profile) {
    ?>
        <div class="profile-page">
            <div class="profile-card">
                <div class="profile-left">
                    <div class="profile-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h3><?php echo $profile['TenNguoiDung'] ?></h3>
                    <p>Khách hàng</p>
                </div>
                <div class="profile-right">
                    <h2>Tài Khoản của tôi</h2>
                    <div class="title-line"></div>
                    <div class="info-row">
                        <i class="fa-regular fa-user"></i>
                        <span>Họ tên</span>
                        <input type="text"
                            name="TenNguoiDung"
                            value="<?php echo $profile['TenNguoiDung'] ?>">
                    </div>
                    <div class="info-row">
                        <i class="fa-regular fa-envelope"></i>
                        <span>Email</span>
                        <input type="email"
                            name="Email"
                            value="<?php echo $profile['Email'] ?>" disabled>
                    </div>
                    <div class="info-row">
                        <i class="fa-solid fa-phone"></i>
                        <span>Số điện thoại</span>
                        <input type="text"
                            name="SoDienThoai"
                            value="<?php echo $profile['SoDienThoai'] ?>" disabled>
                    </div>
                    <div class="info-row">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Địa chỉ</span>
                        <input type="text"
                            name="DiaChi"
                            value="<?php echo $profile['DiaChi'] ?>">
                    </div>
                    <div class="profile-actions">
                        <button href="<?php echo url('AuthController/updateprofile') ?>" class="btn-update">
                            <i class="fa-regular fa-pen-to-square"></i>
                            Cập nhật thông tin
                        </button>

                        <a href="<?php echo url('AuthController/forgot') ?>" class="btn-password">
                            <i class="fa-solid fa-lock"></i>
                            Đổi mật khẩu
                        </a>
                    </div>
                </div>

            </div>

        </div>
    <?php } ?>
</form>


<?php endblock() ?>