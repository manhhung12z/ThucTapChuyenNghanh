<?php
require_once(_DIR_ROOT . '/app/Controllers/Web/WebController.php');
require_once(_DIR_ROOT . '/app/Models/Web/chatbot.php');
class ChatbotController extends WebController
{
    public function ask()
    {
        header('Content-Type: application/json; charset=utf-8');

        $message = trim($_POST['message'] ?? '');
        if(empty($message))
            {
                echo json_encode(['success' => false, 'answer' => 'Vui lòng nhập câu hỏi.']);
                return;
            }
        $chatbotModel = new chatbot();
        $answer = $chatbotModel->get_response($message);
        echo json_encode(['success' => true, 'answer' => $answer]);
    }
}
?>