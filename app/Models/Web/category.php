<?php
require_once('app/Models/Model.php');
class category extends Model
{
    protected $table = 'danhmuc';
    protected $fillable = ['TenDanhMuc', 'MoTa']; // Assuming fields: TenDanhMuc, MoTa
    protected $primaryKey = 'MaDanhMuc';

    public function findbyCategory($MaDanhMuc)
    {
        $sql = "select sp.*,IFNULL(MAX(gg.PhanTram), 0) as PhanTram, (sp.Gia - (sp.Gia * IFNULL(MAX(gg.PhanTram), 0) / 100)) AS GiaSauGiam from sanpham sp  left join chitietgiamgia ct on sp.MaSanPham=ct.MaSanPham left join giamgia gg on gg.MaGiamGia=ct.MaGiamGia and NOW() between gg.NgayBatDau and gg.NgayKetThuc where sp.MaDanhMuc ='$MaDanhMuc' group by sp.MaSanPham";
        return $this->anyfind($sql);
    }
    public function getAllCategories()
    {
        $sql = "SELECT * FROM danhmuc";
        return $this->anyfind($sql);
    }
    public function filterProducts($MaDanhMuc, $sortOption)
    {
        $sql= "";
        if ($sortOption == "") {
            $sql = "SELECT sp.*, IFNULL(MAX(gg.PhanTram), 0) as PhanTram, (sp.Gia - (sp.Gia * IFNULL(MAX(gg.PhanTram), 0) / 100)) AS GiaSauGiam, IFNULL(sales.TongBan, 0) as TongBan FROM sanpham sp 
                -- Gom nhóm tính tổng số lượng bán trước để tránh nhân chéo dòng
                LEFT JOIN (
                    SELECT MaSanPham, SUM(SoLuong) as TongBan 
                    FROM chitietdonhang 
                    GROUP BY MaSanPham
                ) sales ON sp.MaSanPham = sales.MaSanPham
                LEFT JOIN chitietgiamgia ctgg ON sp.MaSanPham = ctgg.MaSanPham 
                LEFT JOIN giamgia gg ON gg.MaGiamGia = ctgg.MaGiamGia AND NOW() BETWEEN gg.NgayBatDau AND gg.NgayKetThuc 
                WHERE 1=1 AND sp.MaDanhMuc = '$MaDanhMuc'
                GROUP BY sp.MaSanPham 
                ORDER BY TongBan DESC 
                LIMIT 8";
        } else if ($sortOption == "price_low_to_high") {
            $sql = "select sp.*,IFNULL(MAX(gg.PhanTram), 0) as PhanTram, (sp.Gia - (sp.Gia * IFNULL(MAX(gg.PhanTram), 0) / 100)) AS GiaSauGiam, SUM(ctdh.SoLuong) as TongBan from sanpham sp left join chitietdonhang ctdh on sp.MaSanPham = ctdh.MaSanPham left join chitietgiamgia ct on sp.MaSanPham=ct.MaSanPham left join giamgia gg on gg.MaGiamGia=ct.MaGiamGia and NOW() between gg.NgayBatDau and gg.NgayKetThuc where 1 and sp.MaDanhMuc='$MaDanhMuc' group by sp.MaSanPham order by GiaSauGiam asc";
        }
        return $this->anyfind($sql);
    }
}
