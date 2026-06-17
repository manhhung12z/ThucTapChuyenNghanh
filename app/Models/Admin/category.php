<?php
require_once(_DIR_ROOT . '/app/Models/Model.php');

class category extends Model {
    protected $table = 'danhmuc';
    protected $fillable = ['MaDanhMuc', 'TenDanhMuc', 'MoTa'];
    protected $primarykey = 'MaDanhMuc';

    public function showcategory()
    {
        return $this->anyfind($this->getSql());
    }

    public function getSql() {
        return "SELECT d.MaDanhMuc AS madanhmuc, 
                       d.TenDanhMuc AS tendanhmuc, 
                       d.MoTa AS mota, 
                       COUNT(s.MaSanPham) AS tongsanpham 
                FROM danhmuc d 
                LEFT JOIN sanpham s ON d.MaDanhMuc = s.MaDanhMuc 
                GROUP BY d.MaDanhMuc, d.TenDanhMuc, d.MoTa
                ORDER BY d.MaDanhMuc DESC";
    }

    public function searchAndPaginate($keyword, $limit, $offset) {
    $keyword = "%" . $keyword . "%";
    $sql = "SELECT d.MaDanhMuc AS madanhmuc, d.TenDanhMuc AS tendanhmuc, d.MoTa AS mota, 
                   COUNT(s.MaSanPham) AS tongsanpham 
            FROM danhmuc d 
            LEFT JOIN sanpham s ON d.MaDanhMuc = s.MaDanhMuc 
            WHERE d.TenDanhMuc LIKE ? OR d.MoTa LIKE ? 
            GROUP BY d.MaDanhMuc, d.TenDanhMuc, d.MoTa 
            ORDER BY d.MaDanhMuc DESC 
            LIMIT ?, ?";
    
    $stmt = $this->dbconnect->prepare($sql);
    $stmt->bind_param("ssii", $keyword, $keyword, $offset, $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);    
}

    public function countSearch($keyword) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT COUNT(*) as total FROM danhmuc WHERE TenDanhMuc LIKE ? OR MoTa LIKE ?";
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}