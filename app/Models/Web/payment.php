<?php
require_once('app/Models/Model.php');
class payment extends Model
{
    protected $table = 'thanhtoan';
    protected $fillable = ['MaThanhToan', 'MaDonHang', 'PhuongThuc', 'TrangThai','NgayThanhToan']; // Assuming fields: MaDonHang, MaNguoiDung, NgayDat, TongTien, TrangThai
    protected $primarykey = 'MaDonHang';
    public function insertpayment($data){
        return $this->insert($data);
    }
    public function updatepayment($data, $id)
    {
        return $this->update($data, $id);
    }
}
