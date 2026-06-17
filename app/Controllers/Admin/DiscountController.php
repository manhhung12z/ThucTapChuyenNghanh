<?php
require_once(_DIR_ROOT . '/app/Controllers/Admin/AdminController.php');
require_once(_DIR_ROOT . '/app/Models/Admin/Discount.php'); // Đảm bảo đúng đường dẫn file Model

class DiscountController extends AdminController
{

    public function index()
    {
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $discountModel = new Discount();
        $discounts = $discountModel->getPaginated($limit, $offset, $discountModel->getSql());
        $total = $discountModel->countAll();
        
        $totalPages = ceil($total / $limit);
        
        return $this->views("discount/index", [
            'discounts' => $discounts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'themeColor' => 'yellow' 
        ]);
    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $discountModel = new Discount();
            $_POST['MaGiamGia'] = $discountModel->generateNextId();
            $result = $discountModel->insert($_POST);
        }
        header("Location: " . _WEB_ROOT . "/admin/DiscountController/index?status=success");
        exit;
    }


    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['MaGiamGia'] ?? ''; 
            $data = [
                'TenGiamGia'  => $_POST['TenGiamGia'] ?? '',
                'PhanTram'    => (int)($_POST['PhanTram'] ?? 0),
                'NgayBatDau'  => $_POST['NgayBatDau'] ?? null,
                'NgayKetThuc' => $_POST['NgayKetThuc'] ?? null
            ];
            
            if (!empty($id)) {
                $discountModel = new Discount();
                $result = $discountModel->update($data, $id);
            }
        }
        header("Location: " . _WEB_ROOT . "/admin/DiscountController/index?status=success");
        exit;
    }

    public function delete()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : null;
        
        if (!empty($id)) {
            $discountModel = new Discount();
            $count = $discountModel->countDelete('chitietgiamgia', 'magiamgia', $id);   
            if ($count > 0) {
                header("Location: " . _WEB_ROOT . "/admin/DiscountController/index?status=error");
                exit;
            } else {
                $discountModel->delete($id);
                header("Location: " . _WEB_ROOT . "/admin/DiscountController/index?status=success");
                exit;
            }
        }
    }

    public function searchAjax() {
        header('Content-Type: application/json'); 
        $keyword = $_GET['q'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6; 
        $offset = ($page - 1) * $limit;
        $discountModel = new Discount();
        $data = $discountModel->searchAndPaginate($keyword, $limit, $offset);
        $total = $discountModel->countSearch($keyword);
        
        echo json_encode([
            'data' => $data,
            'totalPages' => ceil($total / $limit),
            'currentPage' => $page
        ]);
    }
}