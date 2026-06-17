<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styleresetpassword.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>


    <style>
        * {
            box-sizing: border-box;
            /* Đảm bảo padding và border không làm thay đổi kích thước phần tử */
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            z-index: 1;
            /*Số 1 thường là lớp rất thấp, giúp hình tròn này nằm bên dưới các nội dung chính (như chữ, hình ảnh khác) để làm nền.*/
            opacity: 0.45;
            /* ẩn ở dưới nền chứ không bị đậm màu che mất chữ*/
            filter: blur(80px);
            /* Làm nhòe đi 80 pixel */
            animation: floatCircle 8s ease-in-out infinite alternate;
            /* chuyển động mượt mà 2 đầu,vô hạn,tự động đảo chiều */
        }

        .circle-1 {
            width: 300px;
            height: 300px;
            background: #8ec5fc;
            top: 10%;
            /*đẩy hình tròn xuống 10% chiều cao màn hình*/
            left: 15%;
        }

        .circle-2 {
            width: 380px;
            height: 380px;
            background: #e0c3fc;
            bottom: 10%;
            right: 15%;
            animation-delay: 2s;
            /* bắt đầu chuyển động sau 2 giây, tạo hiệu ứng xen kẽ giữa hai hình tròn */
        }

        .reset-card {
            max-width: 420px;
            width: 100%;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 24px;
            padding: 40px 30px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: white;
            position: relative;
            z-index: 2;
            /* Đảm bảo thẻ reset-card nằm trên các hình tròn nền */
            animation: showCard 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            backdrop-filter: blur(20px);
            /* Làm nhòe tất cả các nội dung (chữ, hình ảnh, đốm sáng) nằm phía sau tấm kính này với độ mờ là 20 pixel.*/
        }

        @keyframes showCard {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatCircle {
            0% {
                /* vị trí ban đầu của hình tròn */
                transform: translateY(0);
            }

            100% {
                transform: translateY(-20px);
                /* Di chuyển lên trên 20 pixel */
            }
        }

        .lock-icon {
            width: 76px;
            height: 76px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            font-size: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.25);
            margin: 0 auto 20px;
            /* trên trái phải dưới */
            animation: pulseIcon 2.5s infinite ease-in-out;
        }

        @keyframes pulseIcon {

            0%,
            100% {
                /*transform: scale(1);: Giữ nguyên kích thước gốc 100% của icon.*/
                transform: scale(1);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15), 0 0 0 0 rgba(255, 255, 255, 0.2);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15), 0 0 0 10px rgba(255, 255, 255, 0);
            }
        }

        .reset-card h2 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
            /* giúp các chữ khíp lại */

        }

        .reset-desc {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 6px;
            line-height: 1.5;
            /*khoảng cách dòng khít lại*/
        }

        .reset-email {
            font-size: 15px;
            font-weight: 600;
            color: #ffd60a;
            margin-bottom: 24px;
        }

        /*otp*/
        .otp-box {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 24px;
        }

        .otp-box input {
            width: 100%;
            height: 50px;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            outline: none;
            color: #1f2937;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.25s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .otp-box input:focus {
            background: #ffffff;
            border-color: #6366f1;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }

        .input-group {
            height: 50px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            margin-bottom: 16px;
            color: #4f46e5;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            transition: all 0.25s ease;
        }

        .input-group:focus-within {
            background: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.15);
            transform: translateY(-1px);
        }

        .input-group i {
            font-size: 16px;
            color: #6366f1;
            width: 20px;
            text-align: center;
        }

        .input-group input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            font-size: 15px;
            color: #1f2937;
            margin-left: 12px;
            padding: 0;
        }

        .input-group input::placeholder {
            color: #9ca3af;
        }

        .reset-btn {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 12px;
            margin-top: 10px;
            background: linear-gradient(135deg, #ffffff 0%, #f3e9ff 100%);
            color: #764ba2;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);

        }

        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, #ffffff 0%, #eadaff 100%);
        }

        .back-login {
            display: inline-block;
            margin-top: 24px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .back-login:hover {
            color: white;
            transform: translateX(-3px);
        }

        @media(max-width:480px) {
            body {
                padding: 15px;
            }

            .reset-card {
                padding: 30px 20px;
            }

            .reset-card h2 {
                font-size: 22px;
            }

            .otp-box {
                gap: 6px;
            }

            .otp-box input {
                height: 44px;
                font-size: 18px;
                border-radius: 10px;
            }

            .input-group,
            .reset-btn {
                height: 46px;
                border-radius: 10px;
            }



        }
    </style>
</head>

<body>
    <div class="reset-page">
        <div class="bg-circle circle-1"></div>
        <div class="bg-circle circle-2"></div>
        <div class="reset-card">
            <div class="lock-icon">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h2>Đặt lại mật khẩu</h2>

            <p class="reset-desc">
                Nhập mã OTP đã gửi tới email của bạn
            </p>
            <p class="reset-email">
                <?php echo $_SESSION['reset_email'] ?? ''; ?>
            </p>
            <form action="<?php echo url('AuthController/handleresetpassword'); ?>" method="POST">
                <div class="otp-box">
                    <input type="text" name="otp[]" maxlength="1">
                    <input type="text" name="otp[]" maxlength="1">
                    <input type="text" name="otp[]" maxlength="1">
                    <input type="text" name="otp[]" maxlength="1">
                    <input type="text" name="otp[]" maxlength="1">
                    <input type="text" name="otp[]" maxlength="1">
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-key"></i>
                    <input type="password" name="password" placeholder="Mật khẩu mới">
                    <i class="fa-solid fa-eye toggle-eye"></i>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-shield-halved"></i>
                    <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu">
                    <i class="fa-solid fa-eye toggle-eye"></i>
                </div>
                <button type="submit" class="reset-btn">
                    Đặt lại mật khẩu
                </button>

            </form>
            <a href="<?php echo url('AuthController/login'); ?>" class="back-login">
                ← Quay lại đăng nhập
            </a>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (Flash::has('log_error')) { ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: <?php echo json_encode(Flash::get('log_error')); ?>,
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        </script>
    <?php } ?>
    <?php if (Flash::has('expire_error')) { ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: <?php echo json_encode(Flash::get('expire_error')); ?>,
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        </script>
    <?php } ?>
    <?php if (Flash::has('Otp_error')) { ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: <?php echo json_encode(Flash::get('Otp_error')); ?>,
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        </script>
    <?php } ?>
    <?php if (Flash::has('Password_error')) { ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: <?php echo json_encode(Flash::get('Password_error')); ?>,
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        </script>
    <?php } ?>
    <?php if (Flash::has('success')) { ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: <?php echo json_encode(Flash::get('success')); ?>,
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        </script>
    <?php } ?>
</body>