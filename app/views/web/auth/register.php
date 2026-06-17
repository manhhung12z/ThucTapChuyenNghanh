<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Privia</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        .register-card {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .register-card h2 {
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
        }

        .btn-register {
            background: #667eea;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }

        .btn-register:hover {
            background: #5a67d8;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        body {
            justify-content: center;
            align-items: center;
            display: flex;
            height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);

        }
    </style>
</head>

<body>
    <div class="register-card">
        <div style="text-align:center; margin-bottom:5px;">
            <img src="https://cdn-icons-png.flaticon.com/512/295/295128.png" alt="logo" style="width:70px;">
        </div>
        <h2>Đăng Ký</h2>
        <form action="<?php echo url('AuthController/handleregister'); ?>" method="POST">
            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" class="form-control" placeholder="Nhập họ tên" name="fullname">
                <?php if (isset($errors['fullname'])): ?> <p style="color: red; font-size: 0.8em; margin-top: 4px;" value="<?php echo $old['fullname'] ?? ''; ?>"><?php echo $errors['fullname']; ?></p><?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" placeholder="Nhập email" name="email">
                <?php if (isset($errors['email'])): ?> <p style="color: red; font-size: 0.8em; margin-top: 4px;" value="<?php echo $old['email'] ?? ''; ?>"><?php echo $errors['email']; ?></p><?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="number" class="form-control" placeholder="Nhập số điện thoại" name="phone_number">
                <?php if (isset($errors['phone_number'])): ?> <p style="color: red; font-size: 0.8em; margin-top: 4px;" value="<?php echo $old['phone_number'] ?? ''; ?>" ><?php echo $errors['phone_number']; ?></p><?php endif; ?>

            </div>

            <div style="display: flex; justify-content: space-between; gap:10px; align-items:center;">
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" placeholder="Nhập mật khẩu" name="password">
                <?php if (isset($errors['password'])): ?> <p style="color: red; font-size: 0.8em; margin-top: 4px;"><?php echo $errors['password']; ?></p><?php endif; ?>

                </div>

                <div class="mb-3">
                    <label class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" placeholder="Nhập lại mật khẩu"
                        name="password_confirmation">
                <?php if (isset($errors['password_confirmation'])): ?> <p style="color: red; font-size: 0.8em; margin-top: 4px;"><?php echo $errors['password_confirmation']; ?></p><?php endif; ?>

                </div>
            </div>

            <button type="submit" class="btn btn-register w-100">Đăng ký</button>
        </form>
        <div class="login-link">
            <p>Đã có tài khoản? <a href="<?php echo url('AuthController/login'); ?>">Đăng nhập</a></p>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (!empty($errors)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Thông tin nhập chưa đúng...',
            text: 'Vui lòng kiểm tra lại các ô nhập liệu nhé!',
            confirmButtonText: 'OK'
        });
    </script>

    <?php endif; ?>

</html>