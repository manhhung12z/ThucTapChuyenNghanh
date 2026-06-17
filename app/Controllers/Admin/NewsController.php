<?php
require_once(_DIR_ROOT . '/app/Controllers/Admin/AdminController.php');
require_once(_DIR_ROOT . '/app/Models/admin/news.php'); 

class NewsController extends AdminController
{

    public function index()
    {
        $limit = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        
        $newsModel = new News();
        $newsList = $newsModel->getPaginated($limit, $offset, $newsModel->getSql());
        $total = $newsModel->countAll();
        
        $totalPages = ceil($total / $limit);
        
        return $this->views("news/index", [
            'newsList' => $newsList,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'themeColor' => 'pink' 
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newsModel = new News();
            $_POST['MaTinTuc'] = $newsModel->generateNextId(); 
            $newsModel->insert($_POST);
        }
        header("Location: " . _WEB_ROOT . "/admin/NewsController/index?status=success");
        exit;
    }


    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['MaTinTuc'] ?? '';
            $data = [
                'Tieude'  => $_POST['Tieude'] ?? '',
                'NoiDung' => $_POST['NoiDung'] ?? ''
            ];       

            if (!empty($id)) {
                $newsModel = new News();
                $result = $newsModel->update($data, $id);
            }
        }
        header("Location: " . _WEB_ROOT . "/admin/NewsController/index?status=success");
        exit;
    }

    public function deleteNews()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : null;
        
        if (!empty($id)) {
            $newsModel = new News();
            $newsModel->delete($id);
            header("Location: " . _WEB_ROOT . "/admin/NewsController/index?status=success");
            exit;
        }
        header("Location: " . _WEB_ROOT . "/admin/NewsController/index?status=error");
        exit;
    }


    public function searchAjax() {
        header('Content-Type: application/json'); // Thiết lập header JSON
        
        $keyword = $_GET['q'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6; 
        $offset = ($page - 1) * $limit;
        
        $newsModel = new News();

        // Thực thi gọi các hàm tìm kiếm trong Model News
        $data = $newsModel->searchAndPaginate($keyword, $limit, $offset);
        $total = $newsModel->countSearch($keyword);
        
        echo json_encode([
            'data' => $data,
            'totalPages' => ceil($total / $limit),
            'currentPage' => $page
        ]);
    }
}