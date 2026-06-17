<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Privia</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .forgot {
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
        }

        .form-control {
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
        }

        .form-control::placeholder {
            color: #ddd;
        }

        .form-control:focus {
            box-shadow: none;
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .btn-custom {
            border-radius: 10px;
            background: #fff;
            color: #764ba2;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-custom {
            border-radius: 10px;
            background: #fff;
            color: #764ba2;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: #eee;
            color: red;
        }

        .back-link {
            color: #ddd;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .logo i {
            font-size: 35px;
            color: white;
        }
    </style>
</head>

<body>
    <div class="forgot shadow p-4" style="max-width: 380px;">
        <div class="logo">
            <i class="fa-solid fa-lock"></i>
        </div>
        <h3 class="text-center mb-3">Quên mật khẩu</h3>
        <p class="text-center mb-4" style="font-size: 14px;">
            Nhập email của bạn để nhận liên kết đặt lại mật khẩu
        </p>

        <form action="<?php echo url('AuthController/handleforgot'); ?>" method="POST">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Nhập email của bạn" required>
            </div>

                <button type="submit" class="btn btn-custom w-100 mb-3">
                    Gửi yêu cầu
                </button>
        </form>

        <div class="text-center">
            <a href="<?php echo url('AuthController/login'); ?>" class="back-link">← Quay lại đăng nhập</a>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (Flash::has('Email_error')) { ?>
        <script>
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'warning',
            title: <?php echo json_encode(Flash::get('Email_error')); ?>,
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
            });
            </script>
        <?php } ?>
</body>

</html>