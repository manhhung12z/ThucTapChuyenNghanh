<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');

class PolicyController extends WebController
{
    public function index()
    {

        return $this->views("policy/index", []);
    }
}
