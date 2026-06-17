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
class sync_faq_to_qdrant extends Model
{
    public function run()
    {
        $embeddingService = new embeddingservice();
        $qdrant = new qrantservice();

        $sql = "SELECT MaFAQ, CauHoi, CauTraLoi FROM chatbot_faq";
        $faqs = $this->anyfind($sql);

        $points = [];

        foreach ($faqs as $faq) {
            $content =
                "Câu hỏi: " . $faq['CauHoi'] . "\n" .
                "Câu trả lời: " . $faq['CauTraLoi'];

            $vector = $embeddingService->createEmbedding($content);

            if (!$vector) {
                continue;
            }

            $points[] = [
                "id" => 100000 + (int)$faq['MaFAQ'],
                "vector" => $vector,
                "payload" => [
                    "type" => "faq",
                    "source_id" => (int)$faq['MaFAQ'],
                    "content" => $content
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

$sync = new sync_faq_to_qdrant();
$sync->run();