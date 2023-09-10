<?php
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/EmailClient.php';
include_once '../../config/database.php';

// $database = new Database();
// $db = $database->connect();
// $userID = $_POST['userId'];



function sendWelcomeEmail($db, $email)
{
    $query = "SELECT * FROM users WHERE email=?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return;
    }

    $emailSubject = "Welcome To Scriblo!";
    $message = "";

    $emailSent = EmailClient::sendEmail($user['email'], $emailSubject, $message, "hello@myscriblo.com", "Scriblo Team");

    // echo ResponseHandler::sendResponse(200, $user['email']);
}
