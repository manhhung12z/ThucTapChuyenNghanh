<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Email {
    public static function send($to, $subject, $body) {
        $mail = new PHPMailer(true);
        try {
            // Cấu hình Server
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = "UTF-8";
            // Người gửi & Người nhận
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($to);
            // Nội dung
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            return $mail->send();
        } catch (Exception $e) {
            error_log("Lỗi gửi mail: {$mail->ErrorInfo}");
            return false;
        }
    }
}
?>