<?php
require_once(_DIR_ROOT . '/app/Models/Model.php');

class dashboard extends Model {

    public function getTopRevenueProducts($limit) {

        $sql = "SELECT 
                sp.MaSanPham AS masanpham,
                sp.TenSanPham AS tensanpham,
                SUM(ct.SoLuong) AS daban,
                SUM(ct.SoLuong * ct.DonGia) AS doanhthu
            FROM chitietdonhang ct
            JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham
            JOIN donhang dh ON ct.MaDonHang = dh.MaDonHang
            WHERE dh.TrangThai = 'Giao hàng thành công'
            GROUP BY sp.MaSanPham, sp.TenSanPham
            ORDER BY doanhthu DESC
            LIMIT ?";
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("i", $limit);
        
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLast7DaysRevenue() {
    $sql = "SELECT DATE(NgayDat) AS ngaydat, SUM(TongTien) AS tongtien
            FROM donhang
            WHERE TrangThai = N'Giao hàng thành công'
              AND NgayDat >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 DAY)
              AND NgayDat <= CURRENT_DATE()
            GROUP BY DATE(NgayDat)
            ORDER BY ngaydat ASC";
    $result = $this->dbconnect->query($sql);
    $last7DaysRevenue = [];
    if($result) {
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days")); // Tạo chuỗi ngày theo định dạng Y-m-d
            $last7DaysRevenue[$date] = 0; // Khởi tạo doanh thu mặc định là 0
        }

        while ($row = $result->fetch_assoc()) {
            $last7DaysRevenue[$row['ngaydat']] = (float)$row['tongtien']; // Gán doanh thu vào ngày tương ứng
        }
        return $last7DaysRevenue;
    }
}

public function getThisMonthRevenue() {
    $sql = "SELECT DAY(NgayDat) AS ngay, SUM(TongTien) AS tongtien
            FROM donhang
            WHERE TrangThai = N'Giao hàng thành công'
            AND MONTH(NgayDat) = MONTH(CURRENT_DATE())
            AND YEAR(NgayDat) = YEAR(CURRENT_DATE())
            GROUP BY DAY(NgayDat)
            ORDER BY ngay ASC;";
    $result = $this->dbconnect->query($sql);
    $currentDay = (int)date('d');
    $thisMonthRevenue = [];
    for ($i = 1; $i <= $currentDay; $i++) {
        $thisMonthRevenue[$i] = 0; // gan san = 0 cho cac ngay chua co don hang
    }
        while ($row = $result->fetch_assoc()) {
            $thisMonthRevenue[$row['ngay']] = (float)$row['tongtien']; // Gán doanh thu vào ngày tương ứng
        }
        return $thisMonthRevenue;
    }

    public function getMonthlyRevenueThisYear() {
    $sql = "SELECT 
                MONTH(NgayDat) AS thang,
                SUM(TongTien) AS tongtien
            FROM donhang
            WHERE TrangThai = N'Giao hàng thành công'
              AND YEAR(NgayDat) = YEAR(CURRENT_DATE())
            GROUP BY MONTH(NgayDat)
            ORDER BY thang ASC";
        $result = $this->dbconnect->query($sql);
    if($result) {
        $monthlyRevenue = array_fill(1, 12, 0); // Khởi tạo mảng doanh thu cho 12 tháng
        while ($row = $result->fetch_assoc()) {
            $monthlyRevenue[(int)$row['thang']] = (float)$row['tongtien']; // Gán doanh thu vào tháng tương ứng
        }
        return $monthlyRevenue;
    } else {
        return array_fill(1, 12, 0); // Trả về mảng doanh thu rỗng nếu có lỗi
    }
}



}