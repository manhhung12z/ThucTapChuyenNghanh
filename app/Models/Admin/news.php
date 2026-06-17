<?php
require_once(_DIR_ROOT . '/app/Models/Model.php');

class News extends Model {
    protected $table = 'tintuc';
    protected $primarykey = 'MaTinTuc';
    protected $fillable = ['MaTinTuc', 'Tieude', 'NoiDung']; // Khớp chính xác theo cột trong database của cậu

    /**
     * Lấy toàn bộ danh sách tin tức gốc
     */
    public function showNews()
    {
        return $this->anyfind($this->getSql());
    }

    /**
     * Câu lệnh SQL cơ sở để lấy thông tin hiển thị ở trang quản trị
     */
    public function getSql() {
        return "SELECT tt.MaTinTuc AS matintuc, 
                       tt.Tieude AS tieude, 
                       tt.NoiDung AS noidung
                FROM tintuc tt
                ORDER BY tt.MaTinTuc DESC";
    }

    /**
     * Tìm kiếm nâng cao Ajax kết hợp phân trang dữ liệu tin tức
     */
    public function searchAndPaginate($keyword, $limit, $offset) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT tt.MaTinTuc AS matintuc, 
                       tt.Tieude AS tieude, 
                       tt.NoiDung AS noidung
                FROM tintuc tt
                WHERE tt.Tieude LIKE ? OR tt.NoiDung LIKE ?
                ORDER BY tt.MaTinTuc DESC 
                LIMIT ?, ?";
        
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("ssii", $keyword, $keyword, $offset, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);    
    }

    /**
     * Đếm tổng số lượng bản ghi tìm thấy phục vụ tính toán số trang (Total Pages)
     */
    public function countSearch($keyword) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT COUNT(*) as total 
                FROM tintuc 
                WHERE Tieude LIKE ? OR NoiDung LIKE ?";
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}