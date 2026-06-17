<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');
require_once(_DIR_ROOT . '/app/Models/Web/account.php');
require_once(_DIR_ROOT . '/app/Requests/Web/AuthRequest.php');
require_once(_DIR_ROOT . '/core/auth.php');
require_once(_DIR_ROOT . '/core/Flash.php');
require_once(_DIR_ROOT . '/app/Controllers/Web/CartController.php');
require_once(_DIR_ROOT . '/core/Email.php');

class AuthController extends WebController
{
    public function login()
    {
        return $this->views("auth/login", []);
    }

    public function register()
    {
        return $this->views("auth/register", []);
    }
    public function forgot()
    {
        return $this->views("auth/forgot", []);
    }
    public function reset()
    {
        return $this->views("auth/reset", []);
    }
    public function handlelogin()
    {
        $userModel = new account();
        $errors = [];
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $errors['login'] = "Vui lòng nhập đầy đủ Email và Mật khẩu!";
        } else {
            $userData = $userModel->authenticate($_POST);
            if ($userData) {
                if ($userData['LoaiTaiKhoan'] == "user") {
                    if (isset($_POST['remember1'])) {
                        auth::setUser('user', $userData, true);
                        CartController::mergeGuestCartToDb($userData['MaTaiKhoan']);
                    } else {
                        auth::setUser('user', $userData);
                        CartController::mergeGuestCartToDb($userData['MaTaiKhoan']);
                    }
                    return redirect("HomepageController/index");
                } else if ($userData['LoaiTaiKhoan'] == "admin") {
                    if (isset($_POST['remember1'])) {
                        auth::setUser('admin', $userData, true);
                    } else {
                        auth::setUser('admin', $userData);
                    }
                    return redirect("admin/DashboardController/index");
                } else if ($userData['LoaiTaiKhoan'] == "staff") {
                    if (isset($_POST['remember1'])) {
                        auth::setUser('staff', $userData, true);
                    } else {
                        auth::setUser('staff', $userData);
                    }
                    return redirect("admin/DashboardController/index");
                } else {
                    $errors['login'] = "Tài khoản của bạn không có quyền truy cập!";
                }
            } else {
                $errors['login'] = "Email hoặc mật khẩu không chính xác!";
            }
        }
        return $this->views('auth/login', [
            'errors' => $errors,
            'old'    => $_POST
        ]);
    }


    public function handleregister()
    {
        $user = new account();
        $authRequest = new AuthRequest();
        $errors = $authRequest->validate($_POST, $user);
        if (count($errors) > 0) {
            return $this->views('auth/register', [
                'errors' => $errors,
                'old'    => $_POST // giữ lại dữ liệu user đã nhập
            ]);
        }
        // --- Tạo mã tự tăng ---
        $lastMa = $user->getLastMaTaiKhoan();
        if (!$lastMa) {
            $newMa = 'U_KH01';
        } else {
            $number = (int)preg_replace('/[^0-9]/', '', $lastMa);
            $nextNumber = $number + 1;
            $newMa = 'U_KH' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        }
        $data = [
            'MaTaiKhoan'  => $newMa,
            'TenNguoiDung' => $_POST['fullname'],
            'Email'        => $_POST['email'],
            'SoDienThoai'  => $_POST['phone_number'],
            'MatKhau'      => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'LoaiTaiKhoan'         => 'user'
        ];
        $insert = $user->insert($data);
        if ($insert) {
            $_SESSION['success'] = "Đăng ký tài khoản thành công! ^_^";
            return redirect("AuthController/login");
        } else {
            $errors['system'] = "Lỗi hệ thống, không thể đăng ký lúc này.";
            return $this->views('auth/register', ['errors' => $errors, 'old' => $_POST]);
        }
    }
    public function handleforgot()
    {
        $user = new account();
        if (isset($_POST['email'])) {
            $email = trim($_POST['email'] ?? '');
            $userInfo = $user->findEmail($email);
            if ($userInfo) {
                // Send reset password email
                $otp = (string) random_int(100000, 999999);
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_otp'] = $otp;
                $_SESSION['reset_otp_expire'] = time() + 300;
                // Gửi email chứa OTP
                $emailSender = new Email();
                $subject = "Yêu cầu đặt lại mật khẩu";
                $body = <<<HTML
                <!DOCTYPE html>
                <html lang="vi">
                <head>
                    <meta charset="UTF-8">
                </head>
                <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 30px; background-color: #f4f6fb;">
                    <div style="max-width: 520px; margin: 0 auto; background: #ffffff; border: 1px solid #eeeeee; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                        
                        <div style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 28px 30px; text-align: center;">
                            <div style="font-size: 38px; margin-bottom: 8px;">🔒</div>
                            <h2 style="margin: 0; color: #ffffff; font-size: 22px;">
                                Yêu cầu đặt lại mật khẩu
                            </h2>
                        </div>

                        <div style="padding: 30px;">
                            <p style="margin-top: 0;">Kính gửi Quý khách,</p>
                            
                            <p>
                                Chúng tôi vừa nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng sử dụng mã xác thực (OTP) dưới đây để hoàn tất quá trình:
                            </p>
                            
                            <div style="background: #f3f0ff; border: 1px solid #dcd4ff; padding: 18px; text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 6px; color: #5b3cc4; margin: 24px 0; border-radius: 12px;">
                                $otp
                            </div>
                            
                            <div style="font-size: 13px; color: #666666; margin-top: 28px; padding-top: 18px; border-top: 1px solid #eeeeee;">
                                Nếu bạn không thực hiện yêu cầu này, xin vui lòng bỏ qua email. Tài khoản của bạn vẫn được bảo vệ an toàn.
                                <br><br>
                                Trân trọng,<br>
                                <strong>Đội ngũ Hỗ trợ</strong>
                            </div>
                        </div>
                        
                    </div>
                </body>
                </html>
                HTML;
                $result = $emailSender->send($email, $subject, $body);
                return redirect("AuthController/reset");
            } else {
                Flash::set('Email_error', 'Email không tồn tại!');
                return redirect("AuthController/forgot");
            }
        }
    }
    public function handleresetpassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otpInput = implode('', $_POST['otp'] ?? []);
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            if (!isset($_SESSION['reset_email'], $_SESSION['reset_otp'], $_SESSION['reset_otp_expire'])) {
                Flash::set('log_error', 'Phiên đặt lại mật khẩu không hợp lệ');
                return redirect("AuthController/forgot");
            }
            if (time() > $_SESSION['reset_otp_expire']) {
                unset($_SESSION['reset_otp']);
                unset($_SESSION['reset_otp_expire']);
                Flash::set('expire_error', 'Mã OTP đã hết hạn');
                return redirect("AuthController/forgot");
            }
            if ($otpInput !== $_SESSION['reset_otp']) {
                Flash::set('Otp_error', 'Mã OTP không đúng');
                return redirect("AuthController/reset");
            }
            if ($password !== $confirmPassword) {
                Flash::set('Password_error', 'Mật khẩu nhập lại không khớp');
                return redirect("AuthController/reset");
            }
            $user = new account();
            $user->updatepassword($_SESSION['reset_email'], $password);
            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_otp']);
            unset($_SESSION['reset_otp_expire']);
            Flash::set('success', 'Đặt lại mật khẩu thành công');
            return redirect("AuthController/login");
        }
    }
    public function logout()
    {
        if (isset($_SESSION['admin'])) {
            auth::logout('admin');
        }
        if (isset($_SESSION['staff'])) {
            auth::logout('staff');
        }
        if (isset($_SESSION['user'])) {
            auth::logout('user');
        }
        return redirect("HomepageController/index");
    }
    public function profile()
    {
        $userModel = new account();
        $data = $userModel->profile(auth::handleUser('user'));
        return $this->views("auth/profile", ['profiles' => $data]);
    }
    public function updateprofile()
    {
        $userModel = new account();
        $id=auth::handleUser('user');
        if(isset($_POST['TenNguoiDung']) || isset($_POST['DiaChi']))
            {
                $TenNguoiDung=$_POST['TenNguoiDung'];
                $diachi=$_POST['DiaChi'];
                $userModel->updateprofile($TenNguoiDung,$diachi,$id);
                return redirect('AuthController/profile');
            }
        return redirect('AuthController/profile');
    }
}
