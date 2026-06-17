<?php
require_once(_DIR_ROOT . '/app/Models/Model.php');

class Account extends Model {
    protected $table = 'taikhoan';
    protected $primarykey = 'MaTaiKhoan';
    protected $fillable = ['MaTaiKhoan', 'TenNguoiDung', 'Email', 'MatKhau', 'SoDienThoai', 'LoaiTaiKhoan', 'DiaChi'];

    public function showAccount()
    {
        return $this->anyfind($this->getSql());
    }

    public function getSql() {
        return "SELECT tk.MaTaiKhoan AS mataikhoan, 
                       tk.TenNguoiDung AS tennguoidung, 
                       tk.Email AS email, 
                       tk.SoDienThoai AS sodienthoai,
                       tk.LoaiTaiKhoan AS loaitaikhoan,
                       tk.DiaChi AS diachi,
                       COUNT(dg.MaDanhGia) AS tongdanhgia 
                FROM taikhoan tk
                LEFT JOIN danhgia dg ON tk.MaTaiKhoan = dg.MaTaiKhoan
                GROUP BY tk.MaTaiKhoan
                ORDER BY tk.MaTaiKhoan DESC";
    }

    public function searchAndPaginate($keyword, $limit, $offset) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT tk.MaTaiKhoan AS mataikhoan, 
                       tk.TenNguoiDung AS tennguoidung, 
                       tk.Email AS email, 
                       tk.SoDienThoai AS sodienthoai,
                       tk.LoaiTaiKhoan AS loaitaikhoan,
                       tk.DiaChi AS diachi,
                       COUNT(dg.MaDanhGia) AS tongdanhgia 
                FROM taikhoan tk
                LEFT JOIN danhgia dg ON tk.MaTaiKhoan = dg.MaTaiKhoan
                WHERE tk.TenNguoiDung LIKE ? OR tk.Email LIKE ? OR tk.SoDienThoai LIKE ?
                GROUP BY tk.MaTaiKhoan
                ORDER BY tk.MaTaiKhoan DESC 
                LIMIT ?, ?";
        
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("sssii", $keyword, $keyword, $keyword, $offset, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);    
    }

    public function countSearch($keyword) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT COUNT(*) as total 
                FROM taikhoan 
                WHERE TenNguoiDung LIKE ? OR Email LIKE ? OR SoDienThoai LIKE ?";
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}