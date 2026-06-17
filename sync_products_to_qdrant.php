<?php
if (!defined('_DIR_ROOT')) {
    define('_DIR_ROOT', __DIR__);
    require_once _DIR_ROOT . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(_DIR_ROOT);
    $dotenv->load();
    require_once _DIR_ROOT . '/config/database.php';
}
require_once _DIR_ROOT . '/app/Models/Model.php';
require_once _DIR_ROOT . '/app/Models/Web/embeddingservice.php';
require_once _DIR_ROOT . '/app/Models/Web/qrantservice.php';
class sync_products_to_qdrant extends Model
{
    public function run()
    {
        $embeddingService = new embeddingservice();
        $qdrant = new qrantservice();
        $sql="select * from sanpham";
        $product=$this->anyfind($sql);
        $points=[];
        foreach($product as $sp)
            {
                $content="Tên sản phẩm: " . $sp['TenSanPham'] . "\n" .
                "Giá: " . $sp['Gia'] . "\n" ."Mô tả: " . strip_tags($sp['MoTa']);
                $vector = $embeddingService->createEmbedding($content);
                if (!$vector) {
                continue;
            }
            $points[] = [
                "id" => (int)str_replace('SP_', '', $sp['MaSanPham']),
                "vector" => $vector,
                "payload" => [
                    "type" => "product",
                    "source_id" => $sp['MaSanPham'],
                    "content" => $content,
                    "name" => $sp['TenSanPham'],
                    "price" => $sp['Gia']
                ]
            ];
            }
         if (!empty($points)) {
            $result = $qdrant->upsertPoints($points);

            echo "<pre>";
            print_r($result);
            echo "</pre>";
        } else {
            echo "Không có FAQ sync.";
        }
    }
}
$sync = new sync_products_to_qdrant();
$sync->run();
