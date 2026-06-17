<?php
require_once('app/Models/Model.php');
class Review extends Model
{
    protected $table = 'danhgia';
    protected $fillable = ['MaDanhGia', 'MaSanPham', 'MaTaiKhoan', 'SoSao', 'NoiDung', 'NgayDanhGia']; // Assuming fields: MaDanhGia, MaSanPham, MaKhachHang, SoSao, NoiDung, NgayDanhGia
    protected $primarykey = 'MaDanhGia';
    public function insert_review($data)
    {
        $MaSanPham = isset($data['MaSanPham']) ? $this->dbconnect->real_escape_string($data['MaSanPham']) : '';
        $MaTaiKhoan = isset($data['MaTaiKhoan']) ? $this->dbconnect->real_escape_string($data['MaTaiKhoan']) : '';
        $SoSao = isset($data['SoSao']) ? (int)$data['SoSao'] : 0;
        $NoiDung = isset($data['NoiDung']) ? $this->dbconnect->real_escape_string($data['NoiDung']) : '';
        $NgayDanhGia = date('Y-m-d H:i:s'); // Set current date and time

        $sql = "INSERT INTO {$this->table} (MaSanPham, MaTaiKhoan, SoSao, NoiDung, NgayDanhGia) VALUES ('$MaSanPham', '$MaTaiKhoan', '$SoSao', '$NoiDung', '$NgayDanhGia')";
        return $this->dbconnect->query($sql);
    }
    public function get_reviews_by_product($MaSanPham)
    {
        $MaSanPham = $this->dbconnect->real_escape_string($MaSanPham);
        $sql = "SELECT dg.*, tk.TenNguoiDung 
                FROM `DanhGia` dg 
                JOIN `TaiKhoan` tk ON dg.MaTaiKhoan = tk.MaTaiKhoan 
                WHERE dg.MaSanPham = '$MaSanPham' 
                ORDER BY dg.NgayDanhGia DESC";
        return $this->anyfind($sql);
    }
    public function getReviewsBySummary($MaSanPham)
    {
        $sql = "select count(*) as TongDanhGia,IfNULL(AVG(SoSao),0) as DiemTrungBinh, SUM(CASE WHEN SoSao = 5 THEN 1 ELSE 0 END) as Sao5,
        SUM(CASE WHEN SoSao = 4 THEN 1 ELSE 0 END) as Sao4,
        SUM(CASE WHEN SoSao = 3 THEN 1 ELSE 0 END) as Sao3,
        SUM(CASE WHEN SoSao = 2 THEN 1 ELSE 0 END) as Sao2,
        SUM(CASE WHEN SoSao = 1 THEN 1 ELSE 0 END) as Sao1 from danhgia where MaSanPham='$MaSanPham'";
        return $this->anyfind($sql);
    }
    public function canview($MaTaiKhoan, $MaSanPham)
    {
        $MaTaiKhoan = $this->dbconnect->real_escape_string($MaTaiKhoan);
        $MaSanPham = $this->dbconnect->real_escape_string($MaSanPham);
        $sqlbuy = "select count(*) as SoLanMua from donhang dh join chitietdonhang ctdh on dh.MaDonHang=ctdh.MaDonHang join giaohang gh on dh.MaDonHang=gh.MaDonHang WHERE dh.MaTaiKhoan = '$MaTaiKhoan' 
              AND ctdh.MaSanPham = '$MaSanPham' 
              AND gh.TrangThai = 'Đã giao hàng'";
        $resultBuy = $this->anyfind($sqlbuy);
        $soLanMua = !empty($resultBuy) ? (int)$resultBuy[0]['SoLanMua'] : 0;
        $sqlReview = "SELECT COUNT(*) as SoLanDanhGia 
            FROM danhgia 
            WHERE MaTaiKhoan = '$MaTaiKhoan' 
              AND MaSanPham = '$MaSanPham'";
        $resultReview = $this->anyfind($sqlReview);
        $soLanDanhGia = !empty($resultReview) ? (int)$resultReview[0]['SoLanDanhGia'] : 0;
        return $soLanDanhGia < $soLanMua;
    }
}
