<?php
require_once('app/Models/Model.php');
class account extends Model
{
    protected $table = 'taikhoan';
    protected $fillable = ['MaTaiKhoan','TenNguoiDung', 'Email', 'SoDienThoai', 'MatKhau','LoaiTaiKhoan']; // Assuming fields: Email, MatKhau, Ten (name)
    protected $primaryKey = 'Email';

    public function authenticate($data)
    {
        $email = isset($data['email']) ? $this->dbconnect->real_escape_string($data['email']) : '';//giảm sql injection thay thế hoặc thêm backslash trước ký tự nhạy cảm.
        $password = isset($data['password']) ? $data['password'] : '';
        $sql = "SELECT * FROM {$this->table} WHERE Email='$email' LIMIT 1";
        $result = $this->anyfind($sql);
        if ($result && count($result) > 0) {
            $user = $result[0];
            if (isset($user['MatKhau']) && password_verify($password, $user['MatKhau'])) {
                return $user;
            }
        }
        return false;
    }
    public function exists($column, $value)
    {
        $sql = "select * from {$this->table} where $column='$value'";
        $result = $this->dbconnect->query($sql);
        return ($result && $result->num_rows > 0);
    }
    public function findEmail($email)
    {
        $sql = "select * from {$this->table} where Email='$email' LIMIT 1";
        return $this->anyfind($sql);
    }
    public function updatepassword($email, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE {$this->table} SET MatKhau='$hashedPassword' WHERE Email='$email'";
        return $this->dbconnect->query($sql);
    }
    public function profile($id)
    {
        $sql ="select TenNguoiDung,Email,SoDienThoai,DiaChi from taikhoan where MaTaiKhoan='$id'";
        return $this->anyfind($sql);
    }
    public function updateprofile($TenNguoiDung,$diachi,$mataikhoan){
        $sql = "update taikhoan set TenNguoiDung='$TenNguoiDung', DiaChi='$diachi' where MaTaiKhoan='$mataikhoan'";
        return $this->dbconnect->query($sql);
    }
     public function getLastMaTaiKhoan() {
        // Sắp xếp giảm dần để lấy mã lớn nhất
        $sql = "SELECT MaTaiKhoan FROM TaiKhoan WHERE MaTaiKhoan LIKE 'U_KH%' ORDER BY LENGTH(MaTaiKhoan) DESC, MaTaiKhoan DESC LIMIT 1";;
        $result = $this->dbconnect->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['MaTaiKhoan'];
        }
        return null;
}

}
?>