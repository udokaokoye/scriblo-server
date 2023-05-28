<?php
include_once '../../config/Database.php';
include_once '../../models/User.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/TokenGenerator.php';
include_once '../../utils/EmailClient.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // echo json_encode(['message' => 'User exists']);
    // return;
    if (!isset($_POST['email'])) {
        echo ResponseHandler::sendResponse(400, 'Email not provided');
        return;
    }
    $database = new Database();
    $db = $database->connect();
    // $token = TokenGenerator::generateToken();
    $token = 12345;
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    $currentDateTime = new DateTime();

    // check if there is already a token for this user
    $query = 'SELECT token FROM tokens WHERE email = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$_POST['email']]);
    if ($stmt->rowCount() > 0) {
        // delete the token
        $query = 'DELETE FROM tokens WHERE email = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$_POST['email']]);
    }
    

    // Add 10 minutes to the current date and time
    $expirationDateTime = $currentDateTime->add(new DateInterval('PT10M'));

    // Format the expiration date and time
    $expirationDate = $expirationDateTime->format('Y-m-d H:i:s');

    $query = 'INSERT INTO tokens (token, email, expires) VALUES (?, ?, ?)';
    $stmt = $db->prepare($query);
    $stmt->execute([$hashedToken, $_POST['email'], $expirationDate]); // $_POST['email'] is the email of the user who is requesting a token

    if ($stmt) {
        $emailSent = EmailClient::sendEmail($_POST['email'], 'Scriblo - Your 5 digit verification token', 'Your token is ' . $token, ' hello@scriblo.com', ', Scriblo');
        if ($emailSent) {
            echo ResponseHandler::sendResponse(200, 'Token sent');
            return;
        } else {
            echo ResponseHandler::sendResponse(500, 'Error sending email');
            return;
        }
    } else {
        echo ResponseHandler::sendResponse(500, 'Error generating token');
        return;
    }

} else {
    echo json_encode(['error' => 'Method not allowed']);
}
