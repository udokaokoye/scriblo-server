
<?php
class EmailSender {
    public static function sendEmail($to, $subject, $message, $from, $fromName) {
        // Email headers
        $headers = "From: $fromName <$from>" . "\r\n";
        $headers .= "Reply-To: $from" . "\r\n";
        // $headers .= "MIME-Version: 1.0" . "\r\n";
        // $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";

        // Send email
        $mailSent = mail($to, $subject, $message, $headers);

        // Check if the email was sent successfully
        if ($mailSent) {
            return true;
        } else {
            return false;
        }
    }
}


