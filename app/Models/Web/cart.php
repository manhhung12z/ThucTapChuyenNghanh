<?php
require_once('app/Models/Model.php');
class cart extends Model
{
    protected $table = 'giohang';
    protected $primarykey = 'MaGioHang';
    public function findByUser($userid)
    {
        $userid = $this->dbconnect->real_escape_string($userid);
        $sql = "select * from giohang where MaTaiKhoan='$userid' LIMIT 1";
        $result = $this->anyfind($sql);
        return !empty($result) ? $result[0] : null;
    }
    public function createCart($userid)
    {
        $userId = $this->dbconnect->real_escape_string($userid);
        $maGioHang = 'GH_' . $userId . '_' . time();
        $sql = "insert into  giohang (MaGioHang, MaTaiKhoan, NgayTao) values('$maGioHang','$userId',NOW())";
        $this->dbconnect->query($sql);
        return $maGioHang;
    }
    public function getOrCreateCart($userid)
    {
        $cart = $this->findByUser($userid);
        if ($cart)
            return $cart['MaGioHang'];
        return $this->createCart($userid);
    }
    public function addItem($maGioHang, $maSanPham, $soLuong = 1)
    {
        $maGioHang = $this->dbconnect->real_escape_string($maGioHang);
        $maSanPham = $this->dbconnect->real_escape_string($maSanPham);
        $soLuong = (int) $soLuong;
        $sql = "select * from chitietgiohang where MaGioHang='$maGioHang' and MaSanPham='$maSanPham' LIMIT 1";
        $existing = $this->anyfind($sql);
        if (!empty($existing)) {
            $newsoluong = $existing[0]['SoLuong'] + $soLuong;
            $sql1 = "update chitietgiohang set SoLuong=$newsoluong where MaGioHang='$maGioHang' and MaSanPham='$maSanPham' LIMIT 1";
        } else {
            $maChiTiet = 'CT_' . uniqid();
        $sql1 = "INSERT INTO chitietgiohang (MaGioHang, MaSanPham, SoLuong) VALUES ('$maGioHang', '$maSanPham', $soLuong)";        }
        return $this->dbconnect->query($sql1);
    }
    public function removeItem($maGioHang, $maSanPham)
    {
        $maGioHang = $this->dbconnect->real_escape_string($maGioHang);
        $maSanPham = $this->dbconnect->real_escape_string($maSanPham);
        $sql = "DELETE FROM chitietgiohang WHERE MaGioHang = '$maGioHang' AND MaSanPham = '$maSanPham'";
        return $this->dbconnect->query($sql);
    }
    public function updateItemQuantity($maGioHang, $maSanPham, $soLuong)
    {
        $maGioHang = $this->dbconnect->real_escape_string($maGioHang);
        $maSanPham = $this->dbconnect->real_escape_string($maSanPham);
        $soLuong = (int) $soLuong;
        if ($soLuong <= 0)
            return $this->removeItem($maGioHang, $maSanPham);
        $sql = "UPDATE chitietgiohang SET SoLuong = $soLuong WHERE MaGioHang = '$maGioHang' AND MaSanPham = '$maSanPham'";
        return $this->dbconnect->query($sql);
    }
    public function getCartItems($maGioHang)
    {
        $maGioHang = $this->dbconnect->real_escape_string($maGioHang);
        $sql = "SELECT ct.MaSanPham, ct.SoLuong AS SoLuongMua, sp.TenSanPham, sp.Gia, sp.HinhAnh, (sp.Gia - (sp.Gia * IFNULL(MAX(gg.PhanTram), 0) / 100)) AS GiaSauGiam 
                FROM chitietgiohang ct
                JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham
                LEFT JOIN chitietgiamgia cgg ON sp.MaSanPham = cgg.MaSanPham
                LEFT JOIN giamgia gg ON gg.MaGiamGia = cgg.MaGiamGia AND NOW() BETWEEN gg.NgayBatDau AND gg.NgayKetThuc
                WHERE ct.MaGioHang = '$maGioHang'
                GROUP BY ct.MaSanPham, ct.SoLuong, sp.TenSanPham, sp.Gia, sp.HinhAnh";
        return $this->anyfind($sql);
    }
    public function countItems($maGioHang)
    {
        $maGioHang = $this->dbconnect->real_escape_string($maGioHang);
        $sql = "SELECT IFNULL(SUM(SoLuong), 0) as total FROM chitietgiohang WHERE MaGioHang = '$maGioHang'";
        $result = $this->anyfind($sql);
        return !empty($result) ? (int) $result[0]['total'] : 0;
    }
    public function clearCart($maGioHang)
    {
        $maGioHang = $this->dbconnect->real_escape_string($maGioHang);
        $sql = "DELETE FROM chitietgiohang WHERE MaGioHang = '$maGioHang'";
        return $this->dbconnect->query($sql);
    }

}
