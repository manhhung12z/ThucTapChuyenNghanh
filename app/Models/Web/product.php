<?php
require_once('app/Models/Model.php');
class product extends Model
{
    protected $table = 'sanpham';
    protected $primarykey = 'MaSanPham';
    public function show_products()
    {
        return $this->findAll();
    }
    public function shows_product_discount()
    {
        $sql = "select sp.*, IFNULL(MAX(gg.PhanTram), 0) as PhanTram, (sp.Gia - (sp.Gia * IFNULL(MAX(gg.PhanTram), 0) / 100)) AS GiaSauGiam from sanpham sp join chitietgiamgia ct on sp.MaSanPham=ct.MaSanPham join giamgia gg on gg.MaGiamGia=ct.MaGiamGia where NOW() between gg.NgayBatDau and gg.NgayKetThuc group by sp.MaSanPham";
        return $this->anyfind($sql);
    }

    public function find_product_with_discount($id)
    {
        $sql = "select sp.*, IFNULL(MAX(gg.PhanTram), 0) as PhanTram, (sp.Gia - (sp.Gia * IFNULL(MAX(gg.PhanTram), 0) / 100)) AS GiaSauGiam from sanpham sp left join chitietgiamgia ct on sp.MaSanPham=ct.MaSanPham left join giamgia gg on gg.MaGiamGia=ct.MaGiamGia and NOW() between gg.NgayBatDau and gg.NgayKetThuc where sp.MaSanPham = '$id' group by sp.MaSanPham";
        $result = $this->anyfind($sql);
        return !empty($result) ? $result[0] : null;
    }
    public function findid($id)
    {
        return $this->find($id);
    }
    public function find_best()
    {
        $sql = "SELECT sp.*, IFNULL(MAX(gg.PhanTram), 0) as PhanTram, (sp.Gia - (sp.Gia * IFNULL(MAX(gg.PhanTram), 0) / 100)) AS GiaSauGiam, IFNULL(sales.TongBan, 0) as TongBan FROM sanpham sp 
                -- Gom nhóm tính tổng số lượng bán trước để tránh nhân chéo dòng
                LEFT JOIN (
                    SELECT MaSanPham, SUM(SoLuong) as TongBan 
                    FROM chitietdonhang 
                    GROUP BY MaSanPham
                ) sales ON sp.MaSanPham = sales.MaSanPham
                LEFT JOIN chitietgiamgia ctgg ON sp.MaSanPham = ctgg.MaSanPham 
                LEFT JOIN giamgia gg ON gg.MaGiamGia = ctgg.MaGiamGia AND NOW() BETWEEN gg.NgayBatDau AND gg.NgayKetThuc 
                GROUP BY sp.MaSanPham 
                ORDER BY TongBan DESC 
                LIMIT 8";
        return $this->anyfind($sql);
    }
    public function deleteStock($maSanPham, $soLuong)
    {
        $maSanPham = $this->dbconnect->real_escape_string($maSanPham);
        $soLuong = (int)$soLuong; // Chuyển số lượng sang kiểu số nguyên
        $sql ="UPDATE sanpham SET SoLuong=SoLuong-$soLuong where MaSanPham='$maSanPham'";
        return $this->dbconnect->query($sql);
    }
    public function searchproduct($keyword = '', $maDanhMuc = null,$sort = '')
    {
        $sql = "select sp.*,IFNULL(MAX(gg.PhanTram),0) as PhanTram,(sp.Gia - (sp.Gia * IFNULL(MAX(gg.PhanTram), 0) / 100)) AS GiaSauGiam
          From sanpham sp left join (select MaSanPham, sum(SoLuong) as TongBan from chitietdonhang group by MaSanPham ) sales on sp.MaSanPham=sales.MaSanPham left join chitietgiamgia ct on sp.MaSanPham=ct.MaSanPham left join giamgia gg 
          on gg.MaGiamGia= ct.MaGiamGia and NOW() BETWEEN gg.NgayBatDau AND gg.NgayKetThuc WHERE 1=1";
        if (!empty($keyword)) {
            // Tránh lỗi SQL Injection bằng hàm real_escape_string
            $keyword = $this->dbconnect->real_escape_string($keyword);
            $keyword_lower = mb_strtolower($keyword, 'UTF-8');
           // Dùng BINARY để MySQL bắt buộc khớp chính xác cả dấu tiếng Việt
            $sql .= " AND (LOWER(sp.TenSanPham) LIKE BINARY '%$keyword_lower%' OR LOWER(sp.MoTa) LIKE BINARY '%$keyword_lower%')";
        }
        if (!empty($maDanhMuc)) {
            $maDanhMuc = $this->dbconnect->real_escape_string($maDanhMuc);
            $sql .= " AND sp.MaDanhMuc like '$maDanhMuc'";
        }
        $sql .= " group by sp.MaSanPham";
        if ($sort === 'price_low_to_high') {
            $sql .= " ORDER BY GiaSauGiam ASC";
        } else {
            // Mặc định (khi sort rỗng "") chính là sắp xếp theo Top Sellers
            $sql .= " ORDER BY TongBan DESC";
        }
        return $this->anyfind($sql);
    }
}
