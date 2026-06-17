    <?php
    require_once(_DIR_ROOT . '/app/Models/Model.php');

    class Order extends Model {
        protected $table = 'donhang';
        protected $fillable = ['MaDonHang', 'NgayDat', 'TongTien', 'TrangThai', 'MaTaiKhoan']; // Đã sửa MaNguoiDung -> MaTaiKhoan
        protected $primarykey = 'MaDonHang';

        public function showorder()
        {
            return $this->anyfind($this->getSql());
        }

        public function getSql() {
            // Đã sửa đổi để JOIN trực tiếp từ donhang sang TaiKhoan
            return "SELECT 
                    dh.MaDonHang AS madonhang,
                    dh.NgayDat AS ngaydat,
                    dh.TongTien AS tongtien,
                    dh.TrangThai AS trangthai,
                    dh.MaTaiKhoan AS mataikhoan,
                    tk.TenNguoiDung AS tennguoidung   
                    FROM donhang dh LEFT JOIN TaiKhoan tk ON dh.MaTaiKhoan = tk.MaTaiKhoan
                    ORDER BY dh.NgayDat DESC";
        }

        public function searchAndPaginate($keyword, $limit, $offset) {
            $keyword = "%" . $keyword . "%";
            // Đã sửa cấu trúc JOIN tương tự getSql()
            $sql = "SELECT 
                        dh.MaDonHang AS madonhang,
                        dh.NgayDat AS ngaydat,
                        dh.TongTien AS tongtien,
                        dh.TrangThai AS trangthai,
                        dh.MaTaiKhoan AS mataikhoan,
                        tk.TenNguoiDung AS tennguoidung
                    FROM donhang dh LEFT JOIN TaiKhoan tk ON dh.MaTaiKhoan = tk.MaTaiKhoan
                    WHERE 
                        dh.MaDonHang LIKE ?
                        OR dh.TrangThai LIKE ?
                        OR tk.TenNguoiDung LIKE ?
                    ORDER BY dh.NgayDat DESC
                    LIMIT ?, ?";
            
            $stmt = $this->dbconnect->prepare($sql);
            $stmt->bind_param("sssii", $keyword, $keyword, $keyword, $offset, $limit);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);    
        }

        public function countSearch($keyword) {
            $keyword = "%" . $keyword . "%";
            // Đã sửa cấu trúc JOIN tương tự
            $sql = "SELECT COUNT(*) as total FROM donhang dh
                    LEFT JOIN TaiKhoan tk ON dh.MaTaiKhoan = tk.MaTaiKhoan
                    WHERE 
                        dh.MaDonHang LIKE ?
                        OR dh.TrangThai LIKE ?
                        OR tk.TenNguoiDung LIKE ?";
                    
            $stmt = $this->dbconnect->prepare($sql);
            $stmt->bind_param("sss", $keyword, $keyword, $keyword);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc()['total'];
        }

            // Lấy thông tin đơn hàng kèm thông tin Khách hàng
        public function getOrderWithAccount($id) {
            $sql = "SELECT dh.*, tk.TenNguoiDung, tk.SoDienThoai 
                    FROM donhang dh 
                    LEFT JOIN TaiKhoan tk ON dh.MaTaiKhoan = tk.MaTaiKhoan 
                    WHERE dh.MaDonHang = '$id'";
            return $this->anyfind($sql);
        }
        //  Lấy thông tin giao hàng của đơn
        public function getDeliveryInfo($id) {
            $sql = "SELECT * FROM giaohang WHERE MaDonHang = '$id'";
            return $this->anyfind($sql);
        }

        //  Lấy chi tiết đơn hàng kèm Tên sản phẩm
        public function getOrderItemsWithProductNames($id) {
            $sql = "SELECT ct.*, sp.TenSanPham 
                    FROM chitietdonhang ct 
                    JOIN sanpham sp ON ct.MaSanPham = sp.MaSanPham 
                    WHERE ct.MaDonHang = '$id'";
            return $this->anyfind($sql);
        }
        // Cập nhật mã vận đơn và trạng thái vào bảng giao hàng
        public function updateDeliveryStatus($id, $maVanDon) {
            $sql = "UPDATE giaohang 
                    SET TrangThai = 'Đang vận chuyển', MaGiaoHang = '$maVanDon' 
                    WHERE MaDonHang = '$id'";
            return $this->execute($sql);
        }
        public function pushOrderToGHN($giaohangData, $donhangData, $products, $userInfo = [])
    {
        // Xử lý tách chuỗi địa chỉ để lấy Ward, District, Province ID
        $diaChiGiao = $giaohangData['DiaChiGiao'] ?? '';

        $parts = array_map('trim', explode(',', $diaChiGiao));
        $count = count($parts);// Đếm số phần sau khi tách để xác định vị trí mảng
    
        $to_address = $diaChiGiao;
        $ward_code = ""; 
        $district_id = 0;
        $province_id = 0;

        //  mảng form: Địa chỉ, Phường, Quận, Tỉnh
        if ($count >= 4) {
            $province_id = (int)$parts[$count - 1]; // Phần tử cuối
            $district_id = (int)$parts[$count - 2]; // Kế cuối
            $ward_code   = (string)$parts[$count - 3]; // Kế kế cuối
            
            // Phần còn lại ghép lại làm địa chỉ cụ thể
            $addressParts = array_slice($parts, 0, $count - 3); // Lấy tất cả phần tử trước 3 phần cuối
            $to_address = implode(', ', $addressParts); // Ghép lại thành chuỗi địa chỉ
        }

        // Khởi tạo mảng dữ liệu gửi lên GHN
        $data = [
            "payment_type_id" => 2, // 1: Người gửi trả tiền, 2: Người nhận trả tiền
            "note" => "Gọi điện trước khi giao",
            "required_note" => "CHOXEMHANGKHONGTHU", 
            "to_name" => $userInfo['TenNguoiDung'] ?? $donhangData['TenNguoiDung'] ?? "", 
            "to_phone" => $userInfo['SoDienThoai'] ?? $donhangData['SoDienThoai'] ?? "", 
            "to_address" => $to_address, 
            "to_ward_code" => $ward_code,
            "to_district_id" => $district_id, 
            "to_province_id" => $province_id,
            "weight" => 200, 
            "length" => 10,
            "width" => 10,
            "height" => 10,
            "service_type_id" => 2, 
            "items" => [] 
        ];
        if (!empty($products)) {
            foreach ($products as $pro) {
                $data['items'][] = [
                    "name"     => $pro['TenSanPham'] ?? "Sản phẩm Privia",
                    "quantity" => isset($pro['SoLuong']) ? (int)$pro['SoLuong'] : 1,
                    "price"    => isset($pro['DonGia']) ? (int)$pro['DonGia'] : 0 
                ];
            }
        } else {
            return [
                'success' => false, 
                'message' => "Không thể gửi đơn sang GHN vì đơn hàng này không có sản phẩm!"
            ];
        }
        // Khởi tạo cURL gọi lên GHN
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Token: 8dad4f14-5c4c-11f1-8581-2e18172ecb31", //8dad4f14-5c4c-11f1-8581-2e18172ecb31
                "ShopId: 6464938" 
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return ['success' => false, 'message' => "Lỗi cURL: " . $err];
        }
        
        return json_decode($response, true);
    }
}