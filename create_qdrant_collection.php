<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
require_once __DIR__ . '/app/Models/Web/qrantservice.php';
$qdrant = new qrantservice();
$result = $qdrant->createCollection(2048);
echo "<pre>";
print_r($result);
echo "</pre>";