<?php
require_once(_DIR_ROOT . '/core/controller.php');
require_once(_DIR_ROOT . '/app/Models/Web/category.php');

class WebController extends controller
{
    public function views($view, $data = [])
    {
        $categoryModel = new category();
        $categories = $categoryModel->getAllCategories();
        $data['categories'] = $categories; // Add categories to the data array
        return $this->render("web/$view", $data);
    }
}
?>