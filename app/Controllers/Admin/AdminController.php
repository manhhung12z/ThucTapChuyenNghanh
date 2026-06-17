<?php
require_once(_DIR_ROOT . '/core/controller.php');
class AdminController extends controller
{


        public function views($view, $data = [])
    {
        return $this->render("admin/$view", $data);
    }
        public function index()
    {
        return $this->views("layouts/index", []);
    }
        protected function uploadToCloudinary($fileTempPath) {
        // thông tin Cloudinary 
        $cloudName = 'dthzbnoja'; 
        $uploadPreset = 'gagokhoemanh'; 

        $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

        // 2. Chuẩn bị dữ liệu để gửi đi
        $data = [
            'file' => new CURLFile($fileTempPath),
            'upload_preset' => $uploadPreset 
        ];

        //Sử dụng cURL để bắn request lên Cloudinary API
        $ch = curl_init();// Khởi tạo cURL
        curl_setopt($ch, CURLOPT_URL, $url);// Đặt URL của API
        curl_setopt($ch, CURLOPT_POST, true);// Đặt phương thức POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);// Đặt dữ liệu POST
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// Để nhận phản hồi dưới dạng chuỗi
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        // Bỏ qua kiểm tra SSL (ktra dữ liệu giữa client và server) và nếu chạy localhost bị lỗi

        $response = curl_exec($ch);// Thực thi yêu cầu và nhận phản hồi
        curl_close($ch);// Đóng cURL

        // Phân tích kết quả trả về
        $result = json_decode($response, true);

        // Nếu upload thành công, Cloudinary sẽ trả về một mảng chứa 'secure_url'
        if (isset($result['secure_url'])) {
            return $result['secure_url']; // Đây chính là link ảnh dạng https://res.cloudinary.com/...
        }

        return null; 
    }
}
?>