<?php
class qrantservice
{
    private $baseUrl;
    private $apiKey;
    private $collection;
    public function __construct()
    {
        $this->baseUrl = rtrim($_ENV['QDRANT_URL'], '/');
        $this->apiKey = $_ENV['QDRANT_API_KEY'];
        $this->collection = $_ENV['QDRANT_COLLECTION'];
    }
    private function request($method, $endpoint, $data = null)
    {
        $ch = curl_init($this->baseUrl . $endpoint);
        $headers = [
            "Content-Type: application/json",
            "api-key: " . $this->apiKey
        ];
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers
        ]);
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        return json_decode($response, true);
    }
    public function createCollection($vectorSize)
    {
        return $this->request("PUT", "/collections/" . $this->collection, [
            "vectors" => [
                "size" => $vectorSize,
                "distance" => "Cosine"
            ]
        ]);
    }
    //phương thức post chỉ dùng cho tìm kiếm hơn thế nữa vì vectorsize quá to và phương thwucs get giới hạn nên chỉ dùng post để đẩy dưới dạng json.
    //phương thức put thường dùng để thêm dữ liệu, cập nhật
    public function upsertPoints($points)
    {
        return $this->request("PUT", "/collections/" . $this->collection . "/points", [
            "points" => $points
        ]);
    }
    public function search($vector, $limit = 3)
    {
        return $this->request("POST", "/collections/" . $this->collection . "/points/search", [
            "vector" => $vector, //đây của câu hỏi user gửi lên
            "limit" => $limit,
            "with_payload" => true // gửi và trả về hãy gửi kèm theo dữ liệu gốc (như tiêu đề, nội dung bài viết, link ảnh...) mà bạn đã lưu trong Payload
        ]);
    }
    // [ QDRANT INSTANCE (MÁY CHỦ QDRANT) ]
    //    │
    //    ├─── Collection A (Ví dụ: 'products') -> Quy định cố định: Vector Size & Distance
    //    │       │
    //    │       ├─── Point 1 ──► [ ID: 101 ]
    //    │       │            ──► [ Vector: [0.12, -0.45, 0.88, ...] ]
    //    │       │            ──► [ Payload: {"name": "Áo thun", "price": 150} ]
    //    │       │
    //    │       ├─── Point 2 ──► [ ID: 102 ]
    //    │       │            ──► [ Vector: [0.14, -0.42, 0.81, ...] ]
    //    │       │            ──► [ Payload: {"name": "Áo khoác", "price": 300} ]
    //    │       │
    //    │       └─── Point ...
    //    │
    //    └─── Collection B (Ví dụ: 'articles') -> Quy định cố định: Vector Size & Distance
    //            │
    //            └─── Point 1 ──► [ ID: "uuid-9b1deb4d..." ]
    //                         ──► [ Vector: [0.03, 0.11, -0.95, ...] ]
    //                         ──► [ Payload: {"title": "Học PHP", "views": 1500} ]

}
