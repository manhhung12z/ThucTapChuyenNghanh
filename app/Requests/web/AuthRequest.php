<?php
require_once('app/requests/BaseRequest.php');

class AuthRequest extends BaseRequest
{
    public function validate($data, $user)
    { 
        // Validate họ và tên
        if (empty($data['fullname'])) {
            $this->errors['fullname'] = "Họ tên không được để trống.";
        } elseif (mb_strlen($data['fullname']) < 2) {
            $this->errors['fullname'] = "Họ tên phải có ít nhất 2 ký tự.";
        }

        // Validate email
        if (empty($data['email'])) {
            $this->errors['email'] = "Email không được để trống";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Email phải đúng định dạng";
        } elseif ($user->exists('Email', $data['email'])) {
            $this->errors['email'] = "Email đã có người sử dụng";
        }

        // Validate SDT
        if (empty($data['phone_number'])) {
            $this->errors['phone_number'] = "Số điện thoại không được để trống";
        } elseif ($user->exists('SoDienThoai', $data['phone_number'])) {
            $this->errors['phone_number'] = "Số điện thoại đã có người sử dụng";
        } elseif (strlen($data['phone_number']) != 10 || !ctype_digit($data['phone_number'])) {
            $this->errors['phone_number'] = "Số điện thoại phải có 10 chữ số";
        }

        // Validate Mật khẩu
        if (empty($data['password'])) {
            $this->errors['password'] = "Mật khẩu không được để trống";
        }
        if (empty($data['password_confirmation'])) {
            $this->errors['password_confirmation'] = "Nhập mật khẩu xác nhận";
        } elseif ($data['password'] !== $data['password_confirmation']) {
            $this->errors['password_confirmation'] = "Mật khẩu nhập lại không khớp";
        }

        return $this->errors;
    }
    
} 