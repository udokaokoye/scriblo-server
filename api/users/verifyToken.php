<?php
// verify token
include_once '../../config/Database.php';
include_once '../../models/User.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/TokenGenerator.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    if (!isset($_POST['token']) && !isset($_POST['email'])) {
        echo ResponseHandler::sendResponse(400, 'Token and Email not provided');
        return;
    }
    $database = new Database();
    $db = $database->connect();

    $query = 'SELECT token, expires FROM tokens WHERE email = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$_POST['email']]);
    // check if row exists
    if ($stmt->rowCount() === 0) {
        echo ResponseHandler::sendResponse(404, 'Token not found');
        return;
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $expirationDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $row['expires']);
    $currentDateTime = new DateTime();
    if ($currentDateTime > $expirationDateTime) {
        echo ResponseHandler::sendResponse(400, 'Token expired');
        return;
    }

    $token = $row['token'];
    $tokenVerify = password_verify($_POST['token'], $token);
    if (!$tokenVerify) {
        echo ResponseHandler::sendResponse(400, 'Token is incorrect');
        return;
    } else {
        echo ResponseHandler::sendResponse(200, 'Token verified');
        // delete token from database
        $query = 'DELETE FROM tokens WHERE email = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$_POST['email']]);
        return;
    }
} else {
    echo ResponseHandler::sendResponse(405, 'Method not allowed');
}
