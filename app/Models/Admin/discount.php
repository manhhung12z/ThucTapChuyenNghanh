<?php
require_once(_DIR_ROOT . '/app/Models/Model.php');

class Discount extends Model {
    protected $table = 'giamgia'; 
    protected $primarykey = 'MaGiamGia';
    protected $fillable = ['MaGiamGia', 'TenGiamGia', 'PhanTram', 'NgayBatDau', 'NgayKetThuc'];

    public function showDiscount()
    {
        return $this->anyfind($this->getSql());
    }

    /**
     * Lấy danh sách mã giảm giá kèm tổng số sản phẩm được áp dụng qua bảng trung gian chitietgiamgia
     */
    public function getSql() {
        return "SELECT gg.MaGiamGia AS magiamgia, 
                       gg.TenGiamGia AS tengiamgia, 
                       gg.PhanTram AS phantram, 
                       gg.NgayBatDau AS ngaybatdau, 
                       gg.NgayKetThuc AS ngaykethuc,
                       COUNT(ct.MaSanPham) AS tongsanpham -- Đếm số sản phẩm từ bảng trung gian
                FROM giamgia gg
                LEFT JOIN chitietgiamgia ct ON gg.MaGiamGia = ct.MaGiamGia
                GROUP BY gg.MaGiamGia, gg.TenGiamGia, gg.PhanTram, gg.NgayBatDau, gg.NgayKetThuc
                ORDER BY gg.MaGiamGia DESC";
    }

    /**
     * Tìm kiếm và phân trang (đi qua bảng trung gian chitietgiamgia)
     */
    public function searchAndPaginate($keyword, $limit, $offset) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT gg.MaGiamGia AS magiamgia, 
                       gg.TenGiamGia AS tengiamgia, 
                       gg.PhanTram AS phantram, 
                       gg.NgayBatDau AS ngaybatdau, 
                       gg.NgayKetThuc AS ngaykethuc,
                       COUNT(ct.MaSanPham) AS tongsanpham -- Đếm số sản phẩm từ bảng trung gian
                FROM giamgia gg
                LEFT JOIN chitietgiamgia ct ON gg.MaGiamGia = ct.MaGiamGia
                WHERE gg.MaGiamGia LIKE ? OR gg.TenGiamGia LIKE ? 
                GROUP BY gg.MaGiamGia, gg.TenGiamGia, gg.PhanTram, gg.NgayBatDau, gg.NgayKetThuc
                ORDER BY gg.MaGiamGia DESC 
                LIMIT ?, ?";
        
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("ssii", $keyword, $keyword, $offset, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);    
    }

    /**
     * Đếm tổng số dòng thỏa mãn điều kiện tìm kiếm để phân trang
     */
    public function countSearch($keyword) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT COUNT(*) as total FROM giamgia WHERE MaGiamGia LIKE ? OR TenGiamGia LIKE ?";
    
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}