<?php
require_once(_DIR_ROOT . '/app/Models/Web/embeddingservice.php');
require_once(_DIR_ROOT . '/app/Models/Web/qrantservice.php');
class chatbot
{
    public function get_response($message)
    {
        $apiKey = $_ENV['OPENROUTER_API_KEY'] ?? '';

        if ($apiKey == '') {
            return "Dạ chưa cấu hình API key ạ.";
        }
        $context = $this->findSemanticContext($message);

        if ($context == '') {
            $context = "Không tìm thấy dữ liệu liên quan trong hệ thống shop.";
        }
        $data = [
            "model" => "openrouter/free",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Bạn là chatbot tư vấn cho website bán mỹ phẩm. Nhiệm vụ:
                        - Tư vấn sản phẩm
                        - Hỗ trợ khách hàng

                        Quy tắc bắt buộc:
                            - Chỉ trả lời kết quả cuối cùng cho khách.
                            - Tuyệt đối không giải thích quá trình suy nghĩ.
                            - Tuyệt đối không viết các câu như: Okay, let's see, I think, My job is.
                            - Chỉ dùng tiếng Việt.
                            - Trả lời ngắn gọn, lịch sự, thân thiện.
                            - Không tự tạo chính sách shop, giá, khuyến mãi hoặc tồn kho.
                            - Chỉ dựa vào dữ liệu shop bên dưới.
                            - Nếu dữ liệu shop không có thông tin phù hợp, hãy nói khách liên hệ nhân viên hỗ trợ.
                            - Nếu khách nhập linh tinh, ký tự vô nghĩa hoặc không rõ câu hỏi, chỉ trả lời đúng câu sau:
                            Dạ em chưa hiểu câu hỏi của anh/chị. Anh/chị vui lòng hỏi lại rõ hơn hoặc liên hệ nhân viên hỗ trợ ạ.

                        Dữ liệu shop:
                        $context
                        "
                ],
                [
                    "role" => "user",
                    "content" => $message
                ]
            ], //mesage dùng để lưu giữ cuộc trò chuyện
            //có thể lưu role là assistant câu trả lời của al để lưu dữ cuộc trò chuyện được đồng nhất. 
            "max_tokens" => 300, // giới hạn độ dài trả lời
            "temperature" => 0.7 //độ sáng tạo al
        ];
        $ch = curl_init("https://openrouter.ai/api/v1/chat/completions"); // khởi tạo kết nối đến API của OpenRouter
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, // thiết lập để nhận dữ liệu trả về dưới dạng chuỗi
            CURLOPT_POST => true, // thiết lập phương thức POST để gửi dữ liệu
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json", // định dạng dữ liệu gửi đi là json
                "Authorization: Bearer " . $apiKey // thêm header để xác thực với API key
            ],
            CURLOPT_POSTFIELDS => json_encode($data) // chuyển đổi dữ liệu thành định dạng json để gửi đi
        ]);

        $response = curl_exec($ch); // câu lệnh kết nối và nhận dữ liệu 
        if (curl_errno($ch)) {
            curl_close($ch);
            return "Dạ chatbot đang gặp lỗi kết nối ạ.";
        }

        curl_close($ch);

        $result = json_decode($response, true); // để chuyển định dạng ko phải json
        //API sẽ trả về một Object lớn, câu trả lời của AI được giấu trong mảng choices -> message -> content
        // {
        //   "id": "gen-1717145200-abcdefg",
        //   "object": "chat.completion",
        //   "created": 1717145200,
        //   "model": "google/gemma-2-9b-it:free",
        //   "choices": [
        //     {
        //       "index": 0,
        //       "message": {
        //         "role": "assistant",
        //         "content": "Dạ chào bạn! Shop em có sẵn các dòng son môi, kem chống nắng và kem nền chính hãng ạ. Bạn cần tư vấn sản phẩm cho loại da nào ạ?"
        //       },
        //       "finish_reason": "stop"
        //     }
        //   ],
        //   "usage": {
        //     "prompt_tokens": 120,
        //     "completion_tokens": 42,
        //     "total_tokens": 162
        //   }
        // }

        return $result['choices'][0]['message']['content']
            ?? "Dạ chatbot chưa thể trả lời câu hỏi này ạ.";
    }
    public function findSemanticContext($message)
    {
        $embeddingService = new embeddingservice();
        $qdrant = new qrantservice();
        $vector = $embeddingService->createEmbedding($message);

        if (!$vector) {
            return '';
        }

        $result = $qdrant->search($vector, 3);

        $items = $result['result'] ?? [];

        if (empty($items)) {
            return '';
        }

        $context = "Dữ liệu liên quan từ hệ thống shop:\n";

        foreach ($items as $item) {
            $payload = $item['payload'] ?? [];

            if (!empty($payload['content'])) {
                $context .= "- " . $payload['content'] . "\n\n";
            }
        }

        return $context;
    }
}
