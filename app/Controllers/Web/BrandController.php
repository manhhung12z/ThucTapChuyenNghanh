<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');

class BrandController extends WebController
{
    public function index()
    {

        return $this->views("brand/index", []);
    }
}
