<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include 'connection.php';
include './ResponseHandler.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "INSERT INTO `waitlist` (`email`) VALUES (?) "; // ? is a placeholder
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email); // "s" - string, "i" - int, "d" - double, "b" - blob
    $email = $_POST['email'];
    if ($email == null || $email == "") {
        ResponseHandler::sendResponse(400, "Email cannot be empty");
        exit();
    }
    $stmt->execute();
    // cleck if successfully inserted
    if ($stmt) {
        ResponseHandler::sendResponse(200, "Successfully inserted");
    } else {
        ResponseHandler::sendResponse(500, "Error inserting");
    }
    $stmt->close();
} else {
    echo "Request method not accepted";
}