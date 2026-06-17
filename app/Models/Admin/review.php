<?php
require_once(_DIR_ROOT . '/app/Models/Model.php');

class Review extends Model {
    protected $table = 'danhgia'; 
    protected $fillable = ['MaDanhGia', 'MaTaiKhoan', 'MaSanPham', 'SoSao', 'NoiDung', 'NgayDanhGia']; // Đã sửa MaKhachHang -> MaTaiKhoan
    protected $primarykey = 'MaDanhGia';

    public function showreview()
    {
        return $this->anyfind($this->getSql());
    }

    public function getSql() {
        // Thay thế việc JOIN qua bảng trung gian khachhang bằng cách JOIN thẳng tới TaiKhoan
        return "SELECT dg.MaDanhGia AS madanhgia,
                       dg.SoSao AS sosao,
                       dg.NoiDung AS noidung,
                       dg.NgayDanhGia AS ngaydanhgia,
                       sp.TenSanPham AS tensanpham,
                       tk.TenNguoiDung AS tennguoidung
                FROM danhgia dg
                INNER JOIN sanpham sp ON dg.MaSanPham = sp.MaSanPham
                INNER JOIN taikhoan tk ON dg.MaTaiKhoan = tk.MaTaiKhoan
                ORDER BY dg.NgayDanhGia DESC";
    }

    public function searchAndPaginate($keyword, $limit, $offset) {
        $keyword = "%" . $keyword . "%";
        // Cập nhật cấu trúc INNER JOIN đồng bộ với hàm getSql()
        $sql = "SELECT dg.MaDanhGia AS madanhgia, 
                       dg.SoSao AS sosao, 
                       dg.NoiDung AS noidung, 
                       dg.NgayDanhGia AS ngaydanhgia,
                       sp.TenSanPham AS tensanpham, 
                       tk.TenNguoiDung AS tennguoidung
                FROM danhgia dg
                INNER JOIN sanpham sp ON dg.MaSanPham = sp.MaSanPham
                INNER JOIN taikhoan tk ON dg.MaTaiKhoan = tk.MaTaiKhoan
                WHERE tk.TenNguoiDung LIKE ? OR sp.TenSanPham LIKE ? OR dg.NoiDung LIKE ?
                ORDER BY dg.NgayDanhGia DESC 
                LIMIT ?, ?";
        
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("sssii", $keyword, $keyword, $keyword, $offset, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);    
    }

    public function countSearch($keyword) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT COUNT(*) as total 
                FROM danhgia dg
                INNER JOIN sanpham sp ON dg.MaSanPham = sp.MaSanPham
                INNER JOIN taikhoan tk ON dg.MaTaiKhoan = tk.MaTaiKhoan
                WHERE tk.TenNguoiDung LIKE ? OR sp.TenSanPham LIKE ? OR dg.NoiDung LIKE ?";
                
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}