<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');

class ShowroomController extends WebController
{
    public function index()
    {

        return $this->views("showroom/index", []);
    }
}
