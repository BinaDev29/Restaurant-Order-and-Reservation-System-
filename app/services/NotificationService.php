<?php

class NotificationService
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Send an email notification (Simulated or Real)
     */
    public function sendEmail($to, $subject, $message)
    {
        // In a real environment, use PHPMailer or SendGrid.
        // For standard PHP:
        $headers = 'From: noreply@goldenbar.com' . "\r\n" .
            'Reply-To: support@goldenbar.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        // Simulate sending by writing to a log file for demo purposes if mail server isn't set up
        $logEntry = "[" . date('Y-m-d H:i:s') . "] To: $to | Subject: $subject | Message: $message" . PHP_EOL;
        file_put_contents(__DIR__ . '/../../logs/email_log.txt', $logEntry, FILE_APPEND);

        // Attempt actual send (will fail gracefully if no SMTP provided in php.ini)
        return @mail($to, $subject, $message, $headers);
    }

    /**
     * Send an SMS notification (Simulated)
     */
    public function sendSMS($phone, $message)
    {
        // Requires Twilio or similar API. 
        // We log it to simulate the action.
        $logEntry = "[" . date('Y-m-d H:i:s') . "] SMS To: $phone | Message: $message" . PHP_EOL;
        file_put_contents(__DIR__ . '/../../logs/sms_log.txt', $logEntry, FILE_APPEND);
        return true;
    }

    /**
     * Notify Admin/Staff via Internal System Message
     */
    public function notifyInternal($userId, $message, $senderId = null)
    {
        $stmt = $this->pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        // If sender_id is null, it's a system message. But DB schema might expect foreign key.
        // Let's assume ID 1 is Admin/System for now.
        $senderId = $senderId ?? 1;

        return $stmt->execute([$senderId, $userId, $message]);
    }

    // Specific Business Logic Notifications

    public function sendOrderConfirmation($orderId, $userId)
    {
        // Fetch User
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user) {
            $msg = "Thank you for your order #$orderId! We are preparing it now.";
            $this->sendEmail($user['email'], "Order Confirmation #$orderId", $msg);
            if (!empty($user['phone'])) {
                $this->sendSMS($user['phone'], $msg);
            }
        }
    }

    public function sendReservationConfirmation($reservationId, $userId)
    {
        // Fetch Reservation and User
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user) {
            $msg = "Your table reservation #$reservationId is confirmed. We look forward to seeing you!";
            $this->sendEmail($user['email'], "Reservation Confirmed", $msg);
        }
    }
}
