<?php
require_once(_DIR_ROOT . '/app/Controllers/Admin/AdminController.php');
require_once(_DIR_ROOT . '/app/Models/Admin/category.php');

class CategoryController extends AdminController
{
    public function index()
    {
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $categoryModel = new category();

        $categories = $categoryModel->getPaginated($limit, $offset, $categoryModel->getSql());
        $total = $categoryModel->countAll();
        
        $totalPages = ceil($total / $limit);
        
        return $this->views("category/index", [
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'themeColor' => 'white'
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryModel = new category();
            $_POST['MaDanhMuc'] = $categoryModel->generateNextId();
            $result = $categoryModel->insert($_POST);
        }
        header("Location: " . _WEB_ROOT . "/admin/CategoryController/index?status=success");
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['MaDanhMuc'] ?? '';
            $data = [
                'TenDanhMuc' => $_POST['TenDanhMuc'] ?? '',
                'MoTa'       => $_POST['MoTa'] ?? ''
            ];
            if (!empty($id)) {
                $categoryModel = new category();
                $result = $categoryModel->update($data, $id);
            }
        }
        header("Location: " . _WEB_ROOT . "/admin/CategoryController/index?status=success");
        exit;
    }

    public function deletecategory()
    {
        if (isset($_SESSION['staff'])) {
            header("Location: " . _WEB_ROOT . "/admin/CategoryController/index?status=permission_denied");
            exit;
        }

        $id = isset($_GET['id']) ? trim($_GET['id']) : null;
        
        if (!empty($id)) {
            $categoryModel = new category();
            $count = $categoryModel->countDelete('sanpham', 'madanhmuc', $id);
            if ($count > 0) {
                header("Location: " . _WEB_ROOT . "/admin/CategoryController/index?status=error");
                exit;
            } else {
                $categoryModel->delete($id);
                header("Location: " . _WEB_ROOT . "/admin/CategoryController/index?status=success");
                exit;
            }
        }
    }
    public function searchAjax() {
        header('Content-Type: application/json'); //tbao dang xu ly
        $keyword = $_GET['q'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6; 
        $offset = ($page - 1) * $limit;
        $categoryModel = new category();

        $data = $categoryModel->searchAndPaginate($keyword, $limit, $offset);
        $total = $categoryModel->countSearch($keyword);
        
        echo json_encode([
            'data' => $data,
            'totalPages' => ceil($total / $limit),
            'currentPage' => $page
        ]);
    }
}