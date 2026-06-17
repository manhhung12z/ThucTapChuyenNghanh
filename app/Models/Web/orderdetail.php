<?php
require_once('app/Models/Model.php');
class orderdetail extends Model
{
    protected $table = 'chitietdonhang';
    protected $fillable = ['MaDonHang', 'MaSanPham', 'SoLuong', 'DonGia']; // Assuming fields: MaDonHang, MaNguoiDung, NgayDat, TongTien, TrangThai
    public function insertdetail($data){
        return $this->insert($data);
    }
}
