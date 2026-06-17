<?php
require_once(_DIR_ROOT . '/app/Controllers/Admin/AdminController.php');
require_once(_DIR_ROOT . '/app/Models/Admin/account.php'); 

class AccountController extends AdminController
{
    public function index()
    {
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $accountModel = new Account();
        $accounts = $accountModel->getPaginated($limit, $offset, $accountModel->getSql());
        $total = $accountModel->countAll();
        
        $totalPages = ceil($total / $limit);
        
        return $this->views("account/index", [
            'accounts' => $accounts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'themeColor' => 'green' 
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountModel = new Account();
            $_POST['MaTaiKhoan'] = $accountModel->generateNextId(); 
            $result = $accountModel->insert($_POST);
        }
        header("Location: " . _WEB_ROOT . "/admin/AccountController/index?status=success");
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['MaTaiKhoan'] ?? '';
            $data = [
                'TenNguoiDung' => $_POST['TenNguoiDung'] ?? '',
                'Email'        => $_POST['Email'] ?? '',
                'SoDienThoai'  => $_POST['SoDienThoai'] ?? '',
                'LoaiTaiKhoan' => $_POST['LoaiTaiKhoan'] ?? 'user'
            ];       
            if (!empty($_POST['MatKhau'])) {
                $data['MatKhau'] = password_hash($_POST['MatKhau'], PASSWORD_DEFAULT);
            }

            if (!empty($id)) {
                $accountModel = new Account();
                $result = $accountModel->update($data, $id);
            }
        }
        header("Location: " . _WEB_ROOT . "/admin/AccountController/index?status=success");
        exit;
    }
    public function searchAjax() {
        header('Content-Type: application/json'); // Thông báo định dạng dữ liệu trả về
        $keyword = $_GET['q'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6; 
        $offset = ($page - 1) * $limit;
        $accountModel = new Account();

        // Lấy dữ liệu tìm kiếm phân trang có đếm tổng số đánh giá
        $data = $accountModel->searchAndPaginate($keyword, $limit, $offset);
        $total = $accountModel->countSearch($keyword);
        
        echo json_encode([
            'data' => $data,
            'totalPages' => ceil($total / $limit),
            'currentPage' => $page
        ]);
    }
}