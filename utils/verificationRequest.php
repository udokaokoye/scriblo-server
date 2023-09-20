<?php
include_once './ResponseHandler.php';
// include_once '../../utils/EmailClient.php';
include_once '../config/database.php';
error_reporting(E_ALL);

$method = $_SERVER['REQUEST_METHOD'];


if ($method == 'POST') {
    $db = new Database();
    $conn = $db->connect();
    if ( !isset($_POST['email']) || !isset( $_POST['username']) || empty($_POST['email']) || empty($_POST['username'])) {
        echo ResponseHandler::sendResponse(422, "Email And Username Is Required For Verification Request");
        return;
    }
    $email = $_POST['email'];
    $username = $_POST['username'];
    $additionalInformation = isset($_POST['additionalInformation']) ? $_POST['additionalInformation'] : "";

    $query = "INSERT INTO `verification_requests` (email, username, additionalInformation) VALUES (?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$email, $username, $additionalInformation]);

    if ($stmt) {
        echo ResponseHandler::sendResponse(201, "Request Recieved");
    } else {
         echo ResponseHandler::sendResponse(400, "Cannot Process Request, Try again.");
    }

} else {
    echo ResponseHandler::sendResponse(500, "Method Not Allowed");
    // echo "hello";
}




