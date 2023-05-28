<?php
include_once '../../config/Database.php';
include_once '../../models/User.php';
include_once '../../utils/ResponseHandler.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // echo json_encode(['message' => 'User exists']);
    // return;
    if (!isset($_GET['email'])) {
        echo json_encode(['error' => 'Email not provided']);
        return;
    }
    $database = new Database();
    $db = $database->connect();

    $user = new User($db);
    $userExists = $user->checkIfUserExists($_GET['email']);

    if ($userExists) {
        echo ResponseHandler::sendResponse(200, true);
    } else {
        echo ResponseHandler::sendResponse(200, false);
    }
} else {
    echo json_encode(['error' => 'Method not allowed']);
}