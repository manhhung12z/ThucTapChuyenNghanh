<?php
require_once('app/Models/Model.php');
class transport extends Model
{
    protected $table = 'giaohang';
    protected $fillable = ['MaGiaoHang', 'MaDonHang', 'DiaChiGiao', 'TrangThai', 'NgayGiao']; // Assuming fields: MaDonHang, MaNguoiDung, NgayDat, TongTien, TrangThai
    protected $primarykey = 'MaGiaoHang';
    public function inserttranport($data){
        return $this->insert($data);
    }
}
