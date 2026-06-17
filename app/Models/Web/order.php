<?php
require_once('app/Models/Model.php');
class order extends Model
{
    protected $table = 'donhang';
    protected $fillable = ['MaDonHang', 'MaTaiKhoan', 'NgayDat', 'TongTien', 'TrangThai']; // Assuming fields: MaDonHang, MaTaiKhoan, NgayDat, TongTien, TrangThai
    protected $primarykey = 'MaDonHang';
    public function get_order_history_by_user_id($MaTaiKhoan)
    {
        $sql = "
    SELECT 
        sanpham.*,chitietdonhang.SoLuong,chitietdonhang.DonGia,donhang.MaDonHang,donhang.NgayDat,giaohang.TrangThai,thanhtoan.PhuongThuc FROM donhang JOIN chitietdonhang ON donhang.MaDonHang = chitietdonhang.MaDonHang JOIN sanpham 
        ON chitietdonhang.MaSanPham = sanpham.MaSanPham join giaohang ON donhang.MaDonHang = giaohang.MaDonHang LEFT JOIN thanhtoan ON donhang.MaDonHang = thanhtoan.MaDonHang WHERE donhang.MaTaiKhoan = '$MaTaiKhoan' ORDER BY donhang.NgayDat DESC";
        return $this->anyfind($sql);
    }
    public function insertorder($data)
    {
        return $this->insert($data);
    }

    public function updatedate($data, $id)
    {
        return $this->update($data, $id);
    }
}
