<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');
require_once('app/Models/Web/product.php');
class HomepageController extends WebController
{
    public function index()
    {
        $product = new product();
        $data_sp = $product->shows_product_discount();
        $featured_products = $product->find_best();
        return $this->views("homepage/index", ['data_sp' => $data_sp, 'featured_products' => $featured_products]);
    }

}
?>