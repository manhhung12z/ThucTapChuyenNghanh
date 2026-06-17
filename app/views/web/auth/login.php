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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --text-main: #1f2937;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 900px;
            display: flex;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .brand-section {
            flex: 1;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .brand-section img {
            width: 100px;
            margin-bottom: 25px;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.1));
        }

        .brand-section h1 {
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 15px;
        }

        .brand-section p {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 300px;
            line-height: 1.6;
        }

        .form-section {
            flex: 1.2;
            padding: 60px;
            background: white;
        }

        .login-header {
            margin-bottom: 40px;
        }

        h2 {
            color: var(--text-main);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: #4b5563;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 42px;
            color: #9ca3af;
            font-size: 1rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px 12px 45px;
            border: 1.5px solid #e5e7eb;
            background: #f9fafb;
            transition: all 0.2s;
        }

        .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .btn-login {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .auth-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 850px) {
            .brand-section {
                display: none;
            }

            .auth-container {
                max-width: 450px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="brand-section">
            <img src="https://cdn-icons-png.flaticon.com/512/747/747376.png" alt="logo">
            <h1>Privia Store</h1>
            <p>Khám phá bộ sưu tập mỹ phẩm cao cấp và định hình phong cách cá nhân.</p>
        </div>
        <div class="form-section">
            <div class="login-header">
                <h2>Chào mừng bạn!</h2>
                <p class="text-secondary">Vui lòng đăng nhập vào tài khoản của bạn.</p>
            </div>
            <form action="<?php echo url('AuthController/handlelogin'); ?>" method="POST">
                <div class="input-group-custom">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required autocomplete="username">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="input-group-custom">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password" >
                    <?php if (isset($errors['login'])): ?> <p style="color: red; font-size: 0.8em; margin-top: 4px;"><?php echo $errors['login']; ?></p><?php endif; ?>

                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember1">
                        <label class="form-check-label" for="remember" style="font-size: 0.85rem; color: #4b5563;">
                            Ghi nhớ tôi
                        </label>
                    </div>
                    <a href="<?php echo url('AuthController/forgot'); ?>"
                        style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">Quên
                        mật khẩu?</a>
                </div>
                <button type="submit" class="btn btn-login">Đăng Nhập</button>
            </form>
            <div class="auth-footer">
                <p>Mới đến Privia? <a href="<?php echo url('AuthController/register'); ?>">Đăng ký ngay</a></p>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (isset($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            title: 'Thành công!',
            text: '<?php echo $_SESSION['success']; ?>',
            icon: 'success',
            confirmButtonText: 'Đăng nhập ngay',
            confirmButtonColor: '#3085d6'
        });
    </script>
    <?php 
        unset($_SESSION['success']); 
    ?>
    <?php endif; ?>
</html>