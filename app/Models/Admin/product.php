<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
require_once(_DIR_ROOT . '/app/Models/Model.php');

class product extends Model {
    protected $table = 'sanpham';
    protected $fillable = ['MaSanPham', 'TenSanPham', 'Gia', 'SoLuong', 'TrangThai', 'MaDanhMuc', 'MoTa', 'HinhAnh'];
    protected $primarykey = 'MaSanPham';

    public function showproduct() {
        return $this->anyfind($this->getSql());
    }

    public function getSql() {
        return "SELECT s.MaSanPham AS masanpham,
                    s.MaDanhMuc AS madanhmuc,
                    s.TenSanPham AS tensanpham,
                    s.Gia AS gia, 
                    s.Soluong AS tonkho, 
                    s.TrangThai AS trangthai,
                    s.MoTa AS mota,
                    s.HinhAnh AS hinhanh,
                    d.TenDanhMuc AS tendanhmuc,
                    sg.MaGiamGia AS magiamgia
                FROM sanpham s 
                LEFT JOIN danhmuc d ON s.MaDanhMuc = d.MaDanhMuc 
                LEFT JOIN chitietgiamgia sg ON s.MaSanPham = sg.MaSanPham
                ORDER BY s.MaSanPham DESC";
    }

    public function searchAndPaginate($keyword, $limit, $offset) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT s.MaSanPham AS masanpham, 
                    s.MaDanhMuc AS madanhmuc,
                    s.TenSanPham AS tensanpham, 
                    s.Gia AS gia, 
                    s.SoLuong AS tonkho, 
                    s.TrangThai AS trangthai,
                    s.MoTa AS mota,
                    s.HinhAnh AS hinhanh,
                    d.TenDanhMuc AS tendanhmuc,
                    sg.MaGiamGia AS magiamgia
                FROM sanpham s 
                LEFT JOIN danhmuc d ON s.MaDanhMuc = d.MaDanhMuc 
                LEFT JOIN chitietgiamgia sg ON s.MaSanPham = sg.MaSanPham
                WHERE s.TenSanPham LIKE ? OR d.TenDanhMuc LIKE ? 
                ORDER BY s.MaSanPham DESC 
                LIMIT ?, ?";
        
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("ssii", $keyword, $keyword, $offset, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);    
    }

    public function countSearch($keyword) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT COUNT(*) as total 
                FROM sanpham s 
                LEFT JOIN danhmuc d ON s.MaDanhMuc = d.MaDanhMuc 
                WHERE s.TenSanPham LIKE ? OR d.TenDanhMuc LIKE ?";
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
    public function saveDiscount($maSanPham, $maGiamGia) {
        $sqlDelete = "DELETE FROM chitietgiamgia WHERE MaSanPham = ?";
        $stmtDel = $this->dbconnect->prepare($sqlDelete);
        $stmtDel->bind_param("s", $maSanPham);
        $stmtDel->execute();
        $stmtDel->close();
        if (!empty($maGiamGia)) {
            $sqlInsert = "INSERT INTO chitietgiamgia (MaSanPham, MaGiamGia) VALUES (?, ?)";
            $stmtIns = $this->dbconnect->prepare($sqlInsert);
            $stmtIns->bind_param("ss", $maSanPham, $maGiamGia);
            $stmtIns->execute();
            $stmtIns->close();
        }
    }

    public function importFromExcel($filePath) {
        if (!class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
            return [
                'success' => false, 
                'message' => 'Thư viện PhpSpreadsheet chưa được tải thành công. Hãy kiểm tra lại file autoload.'
            ];
        }

        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(); 

            if (count($rows) <= 1) {
                return ['success' => false, 'message' => 'File Excel trống hoặc không có dữ liệu sản phẩm.'];
            }

            $successCount = 0;
            $dbErrors = []; // Mảng gom các dòng bị lỗi DB nếu có

            $sql = "INSERT INTO sanpham (MaSanPham, TenSanPham, MaDanhMuc, Gia, SoLuong, TrangThai, MoTa, HinhAnh) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->dbconnect->prepare($sql);
            if (!$stmt) {
                return ['success' => false, 'message' => 'Lỗi SQL Prepare: ' . $this->dbconnect->error];
            }

            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                if (empty(trim($row[0] ?? ''))) {
                    continue;
                }

                $masanpham  = $this->generateNextId(); // Sinh mã tự động (Ví dụ: SP_07)
                $tensanpham = trim($row[0]);
                $madanhmuc  = trim($row[1] ?? ''); 
                $gia        = !empty($row[2]) ? floatval($row[2]) : 0.0;
                $tonkho     = !empty($row[3]) ? intval($row[3]) : 0;
                $trangthai  = trim($row[4] ?? 'Còn hàng');
                $mota       = trim($row[5] ?? '');
                $hinhanh    = 'default_product.png'; 

                $validStatus = ['Còn hàng', 'Bán chạy', 'Mới', 'Cao cấp', 'Giảm giá'];
                if (!in_array($trangthai, $validStatus)) {
                    $trangthai = 'Còn hàng';
                }

                $stmt->bind_param("sssdisss", $masanpham, $tensanpham, $madanhmuc, $gia, $tonkho, $trangthai, $mota, $hinhanh);
                
                if ($stmt->execute()) {
                    $successCount++;
                } else {
                    $dbErrors[] = "Dòng " . ($i + 1) . " (Sản phẩm: $tensanpham): " . $stmt->error;
                }
            }

            $stmt->close();
            
            // Trả về kết quả chi tiết cho Controller
            if ($successCount > 0) {
                if (!empty($dbErrors)) {
                    return [
                        'success' => true,
                        'message' => "Đã nhập thành công $successCount dòng. Các dòng thất bại: " . implode('; ', $dbErrors)
                    ];
                }
                return ['success' => true, 'message' => 'Dữ liệu Excel đã được nhập vào hệ thống thành công!'];
            } else {
                return [
                    'success' => false,
                    'message' => 'Không có dòng nào được lưu vào Database. Chi tiết lỗi: ' . implode('; ', $dbErrors)
                ];
            }

        } catch (\Exception $e) {
            // Đẩy trực tiếp lỗi hệ thống hoặc lỗi thư viện ra ngoài
            return ['success' => false, 'message' => 'Lỗi đọc file Excel: ' . $e->getMessage()];
        }
    }
}