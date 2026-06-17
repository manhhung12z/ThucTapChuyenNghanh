<?php
class embeddingservice{
    public function createEmbedding($text)
    {
        $apiKey = $_ENV['OPENROUTER_API_KEY'] ?? '';

        if ($apiKey == '') {
            return null;
        }

        $data = [
            "model" => "nvidia/llama-nemotron-embed-vl-1b-v2:free",
            "input" => $text
            //model, input không thể tự đặt mà mặc định
            //ngoài ra còn có ''dimension = $vector
            //
        ];
        $ch = curl_init("https://openrouter.ai/api/v1/embeddings");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, // thiết lập để nhận dữ liệu trả về dưới dạng chuỗi
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json", // định dạng dữ liệu gửi đi là json
                "Authorization: Bearer " . $apiKey // thêm header để xác thực với API key
            ],
            CURLOPT_POSTFIELDS => json_encode($data) // chuyển đổi dữ liệu thành định dạng json để gửi đi
        ]);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        $result = json_decode($response, true);
            //         {
            //   "object": "list",
            //   "data": [
            //     {
            //       "object": "embedding",
            //       "index": 0,
            //       "embedding": [
            //         0.0023064255,
            //         -0.009327924,
            //         0.015797347,
            //         "...",
            //         -0.007865243
            //       ]
            //     }
            //   ],
            //   "model": "text-embedding-3-small",
            //   "usage": {
            //     "prompt_tokens": 8,
            //     "total_tokens": 8
            //   }
            // }


        return $result['data'][0]['embedding'] ?? null;

    }
}
?>