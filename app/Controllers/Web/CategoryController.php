<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');
require_once('app/Models/Web/product.php');
require_once(_DIR_ROOT . '/app/Models/Web/category.php');
class CategoryController extends WebController
{
    public function index()
    {
        $categoryModel = new category();
        $categories = $categoryModel->getAllCategories();
        $productModel = new product();

        $selectedSort = $_GET['sort'] ?? '';
        $MaDanhMuc = $_GET['MaDanhMuc'] ?? null;
        $keyword =$_GET['keyword']??'';
        $products = $productModel->searchproduct($keyword, $MaDanhMuc, $selectedSort);
        // if($MaDanhMuc){
        //     if ($selectedSort) {
        //         $products = $categoryModel->filterProducts($MaDanhMuc, $selectedSort);
        //     } else {
        //         $products = $categoryModel->findbyCategory($MaDanhMuc);
        //     }
        // } else {
        //     $products = [];
        // }
        return $this->views("category/index", ['categories' => $categories, 'products' => $products, 'selectedSort' => $selectedSort,'currentCategory' => $MaDanhMuc]);
    }

}
?>