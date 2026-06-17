<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');
require_once(_DIR_ROOT . '/app/Models/Web/product.php');
require_once(_DIR_ROOT . '/app/Models/Web/review.php');
class ProductController extends WebController
{
    public function index()
    {
        $MaSanPham = $_GET['MaSanPham'] ?? null;

        if ($MaSanPham) {
            $productModel = new product();
            $reviewModel = new review();
            $reviews = $reviewModel->get_reviews_by_product($MaSanPham);
            $productdetail = $productModel->find_product_with_discount($MaSanPham);
            $reviewSummary = $reviewModel->getReviewsBySummary($MaSanPham);

            if ($productdetail) {
                return $this->views('product/index', ['data' => $productdetail, 'reviews' => $reviews, 'reviewSummary' => $reviewSummary]);
            }
        }
    }
}
