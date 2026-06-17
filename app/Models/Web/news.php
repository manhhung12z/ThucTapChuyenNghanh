<?php
require_once('app/Models/Model.php');

class news extends Model {
    
    public function __construct()
    {
        parent::__construct();
        $this->table = 'tintuc';       
        $this->primarykey = 'MaTinTuc'; // Sửa lại đúng viết hoa/thường theo database
        $this->fillable = ['MaTinTuc', 'TieuDe', 'NoiDung']; 
    }

    /**
     * Lấy danh sách tin tức phân trang, sắp xếp theo mã tin mới nhất (TT_01, TT_02)
     */
    public function getNewsList($limit, $offset)
    {
        // Cắt bỏ 3 ký tự đầu ("TT_") và ép kiểu phần số còn lại để sắp xếp từ bài mới -> bài cũ
        $sql = "SELECT MaTinTuc, TieuDe, NoiDung 
                FROM {$this->table} 
                ORDER BY CAST(SUBSTRING(MaTinTuc, 4) AS UNSIGNED) DESC";
        
        return $this->getPaginated($limit, $offset, $sql);
    }
}
?>