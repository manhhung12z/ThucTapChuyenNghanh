<?php
require_once(_DIR_ROOT . '/app/Controllers/Admin/AdminController.php');
require_once(_DIR_ROOT . '/app/Models/admin/order.php'); 

class OrderController extends AdminController
{
    public function index()
    {
        $limit = 6; 
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $orderModel = new Order();
        $orders = $orderModel->getPaginated($limit, $offset, $orderModel->getSql());
        $total = $orderModel->countAll();
        
        $totalPages = ceil($total / $limit);
        
        return $this->views("order/index", [
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'themeColor' => 'red' 
        ]);
    }

    public function update()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['MaDonHang'] ?? ''; 
        $trangThai = $_POST['TrangThai'] ?? '';
        
        if (!empty($id)) {
            $orderModel = new Order();
            $orderModel->update(['TrangThai' => $trangThai], $id);
            if ($trangThai === 'Đang giao hàng') {
                $donhangList  = $orderModel->getOrderWithAccount($id);
                $giaohangList = $orderModel->getDeliveryInfo($id);
                $products     = $orderModel->getOrderItemsWithProductNames($id);
                // Kiểm tra xem đơn hàng và địa chỉ giao có tồn tại không
                if (!empty($donhangList) && !empty($giaohangList)) {
                    $donhangData  = $donhangList[0];
                    $giaohangData = $giaohangList[0];
                    // call api
                    $apiResponse = $orderModel->pushOrderToGHN($giaohangData, $donhangData, $products);
                    
                    // ghi log xem phan hoi
                    $logData = "[" . date('Y-m-d H:i:s') . "] Đơn hàng: " . $id . "\n" . print_r($apiResponse, true) . "\n------------------\n";
                    file_put_contents('ghn_api_log.txt', $logData, FILE_APPEND);
                    $maVanDon = "";
                    // Xử lý mã vận đơn trả về
                    if (isset($apiResponse['code']) && $apiResponse['code'] == 200) {
                        $maVanDon = $apiResponse['data']['order_code']; 
                    } else {
                        // Mã giả lập nếu lỗi API
                        $maVanDon = "GHN" . strtoupper(substr(md5($id), 0, 7)); 
                    }
                    $orderModel->updateDeliveryStatus($id, $maVanDon);
                }
            }
        }
    }
    header("Location: " . _WEB_ROOT . "/admin/OrderController/index?status=success");
}

    public function searchAjax() {
        header('Content-Type: application/json');
        $keyword = $_GET['q'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6; 
        $offset = ($page - 1) * $limit;
        $orderModel = new Order();
        $data = $orderModel->searchAndPaginate($keyword, $limit, $offset);
        $total = $orderModel->countSearch($keyword);
        
        echo json_encode([
            'data' => $data,
            'totalPages' => ceil($total / $limit),
            'currentPage' => $page
        ]);
    }
}
