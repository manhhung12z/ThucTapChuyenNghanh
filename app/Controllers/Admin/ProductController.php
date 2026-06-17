<?php
require_once(_DIR_ROOT . '/app/Controllers/Admin/AdminController.php');
require_once(_DIR_ROOT . '/app/Models/Admin/product.php'); 
require_once(_DIR_ROOT . '/app/Models/Admin/category.php'); 
require_once(_DIR_ROOT . '/app/Models/Admin/discount.php'); 

class ProductController extends AdminController
{
    public function index()
    {
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        
        $productModel = new product(); 
        $categoryModel = new category();
        $discountModel = new discount();
        $discounts = $discountModel->findAll();
        $products = $productModel->getPaginated($limit, $offset, $productModel->getSql());
        $categories = $categoryModel->findAll();
        $total = $productModel->countAll();
        
        $totalPages = ceil($total / $limit);
        
        return $this->views("product/index", [
            'products' => $products,
            'categories' => $categories,
            'currentPage' => $page,
            'discounts' => $discounts,
            'totalPages' => $totalPages,
            'themeColor' => 'blue'
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new product();
            $data = [
                'MaSanPham' => $productModel->generateNextId(),
                'TenSanPham' => $_POST['TenSanPham'] ?? '',
                'Gia' => $_POST['Gia'] ?? 0,
                'SoLuong' => $_POST['SoLuong'] ?? 0,
                'MaDanhMuc' => $_POST['MaDanhMuc'] ?? '',
                'TrangThai' => $_POST['TrangThai'] ?? '',
                'MoTa' => $_POST['MoTa'] ?? ''
            ];

            if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['error'] === UPLOAD_ERR_OK) {
                $uploadedUrl = $this->uploadToCloudinary($_FILES['HinhAnh']['tmp_name']);
                if ($uploadedUrl) {
                    $data['HinhAnh'] = $uploadedUrl;
                }
            }

            $result = $productModel->insert($data);
            if ($result) {
            $maGiamGia = $_POST['MaGiamGia'] ?? '';
            if (!empty($maGiamGia)) {
                $productModel->saveDiscount($data['MaSanPham'], $maGiamGia);
            }
        }
    }
        header("Location: " . _WEB_ROOT . "/admin/ProductController/index?status=success");
        exit;
    }

    public function update()
    {
        $id = $_POST['MaSanPham'] ?? null;

        $data = [
            'TenSanPham' => $_POST['TenSanPham'] ?? '',
            'Gia' => $_POST['Gia'] ?? 0,
            'SoLuong' => $_POST['SoLuong'] ?? 0,
            'MaDanhMuc' => $_POST['MaDanhMuc'] ?? '',
            'TrangThai' => $_POST['TrangThai'] ?? '',
            'MoTa' => $_POST['MoTa'] ?? ''
        ];

        if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['error'] === UPLOAD_ERR_OK) {
            $uploadedUrl = $this->uploadToCloudinary($_FILES['HinhAnh']['tmp_name']);
            if ($uploadedUrl) {
                $data['HinhAnh'] = $uploadedUrl;
            }
        }

        $productModel = new product();
        $result = $productModel->update($data, $id);
        if ($result && $id) {
            $productModel->saveDiscount($id, $_POST['MaGiamGia'] ?? '');
            header("Location: " . _WEB_ROOT . "/admin/ProductController/index?status=success");
        } else {
            header("Location: " . _WEB_ROOT . "/admin/ProductController/index?status=error");
        }
        exit();
    }

    public function delete() // Đổi tên cho nhất quán với sản phẩm
    {
        if (isset($_SESSION['staff'])) {
            header("Location: " . _WEB_ROOT . "/admin/ProductController/index?status=permission_denied");
            exit;
        }

        $id = isset($_GET['id']) ? trim($_GET['id']) : null;
        
        if (!empty($id)) {
            $productModel = new product();
            $productModel->delete($id);
            header("Location: " . _WEB_ROOT . "/admin/ProductController/index?status=success");
            exit;
        }
    }

    public function searchAjax() {
        header('Content-Type: application/json');
        $keyword = $_GET['q'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6; 
        $offset = ($page - 1) * $limit;

        $model = new product();
        $categoryModel = new category();
        $data = $model->searchAndPaginate($keyword, $limit, $offset);
        $total = $model->countSearch($keyword);
        
        echo json_encode([
            'data' => $data,
            'totalPages' => ceil($total / $limit),
            'currentPage' => $page
        ]);
    }
    public function importExcel() {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
            $file = $_FILES['excelFile'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Lỗi tải file lên server (Mã lỗi: ' . $file['error'] . ')'
                ]);
                exit; 
            }
            $filePath = $file['tmp_name'];
            $productModel = new product();
            
            $result = $productModel->importFromExcel($filePath);
            
            echo json_encode($result);
            exit; 
        }
        echo json_encode([
            'success' => false,
            'message' => 'Yêu cầu không hợp lệ.'
        ]);
        exit;
    }
}