<?php
session_start ();
define("_DIR_ROOT", __DIR__);

// Define web root
$folder = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', _DIR_ROOT));
$isHttps = (
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
);
$web_root = ($isHttps ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $folder;
define('_WEB_ROOT', $web_root);

require_once "core/controller.php";
require_once "helpers/index.php";
require_once "app/App.php";
require_once _DIR_ROOT . '/vendor/autoload.php';
if (file_exists(_DIR_ROOT . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(_DIR_ROOT);
    $dotenv->safeLoad();
}
require_once _DIR_ROOT . '/config/mail.php';
require_once _DIR_ROOT . '/core/Email.php';
require_once(_DIR_ROOT . '/core/checkout.php');
$app = new App();
?>