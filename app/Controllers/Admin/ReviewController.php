<?php
require_once(_DIR_ROOT . '/app/Controllers/Admin/AdminController.php');
require_once(_DIR_ROOT . '/app/Models/Admin/review.php');

class ReviewController extends AdminController
{
    public function index()
    {
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $reviewModel = new review();
        $reviews = $reviewModel->getPaginated($limit, $offset, $reviewModel->getSql());
        $total = $reviewModel->countAll();
        $totalPages = ceil($total / $limit);
        return $this->views("review/index", [
            'reviews' => $reviews,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'themeColor' => 'sky' 
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['MaDanhGia'] ?? '';
            $data = [
                'SoSao'   => $_POST['SoSao'] ?? 5,
                'NoiDung' => $_POST['NoiDung'] ?? ''
            ];
            
            if (!empty($id)) {
                $reviewModel = new review();
                $result = $reviewModel->update($data, $id);
            }
        }
        header("Location: " . _WEB_ROOT . "/admin/ReviewController/index?status=success");
        exit;
    }

    public function deletereview()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : null;
        
        if (!empty($id)) {
            $reviewModel = new review();
            $result = $reviewModel->delete($id);
            
            if ($result) {
                header("Location: " . _WEB_ROOT . "/admin/ReviewController/index?status=success");
            } else {
                header("Location: " . _WEB_ROOT . "/admin/ReviewController/index?status=error");
            }
            exit;
        }
        header("Location: " . _WEB_ROOT . "/admin/ReviewController/index");
        exit;
    }

    public function searchAjax() {
        header('Content-Type: application/json');
        $keyword = $_GET['q'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6; 
        $offset = ($page - 1) * $limit;
        $reviewModel = new review();
        $data = $reviewModel->searchAndPaginate($keyword, $limit, $offset);
        $total = $reviewModel->countSearch($keyword);
        
        echo json_encode([
            'data' => $data,
            'totalPages' => ceil($total / $limit),
            'currentPage' => $page
        ]);
    }
}