<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');
require_once('app/Models/Web/news.php'); 

class NewsController extends WebController
{
    public function index()
    {
        $newsModel = new news();
        
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;

        $limit = 6; 
        $offset = ($currentPage - 1) * $limit;

        $totalNews = $newsModel->countAll();
        $totalPages = ceil($totalNews / $limit);

        $news_list = $newsModel->getNewsList($limit, $offset);

        return $this->views("news/index", [
            'news_list'   => $news_list,
            'totalPages'  => $totalPages,
            'currentPage' => $currentPage
        ]);
    }

    public function detail()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if (empty($id)) {
            header('Location: ' . WEB_ROOT . '/NewsController');
            exit;
        }

        $newsModel = new news();
        $news = $newsModel->find($id);

        if (!$news) {
            die('Bài viết này không tồn tại hoặc đã bị xóa!');
        }

        // ĐÃ SỬA LỖI: Trả về view "news/detail" thay vì "news/index"
        return $this->views("news/detail", ['news' => $news]);
    }
}
?>