<?php
include_once '../../config/database.php';
include_once '../../models/User.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/JwtUtility.php';



$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    if (!isset($_POST['action'])) {
        echo ResponseHandler::sendResponse(400, 'action is required');
        return;
    }
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $result = null;
    $action = $_POST['action'];

    if ($action == 'followuser') {
        $result = $user->followUser($_POST['userId'], $_POST['followId'], $_POST['date']);
    }

    if ($action == 'unfollowuser') {
        $result = $user->unfollowUser($_POST['userId'], $_POST['followId']);
    }

    if ($result) {
        echo ResponseHandler::sendResponse(200, 'Action Performed');
    } else {
        echo ResponseHandler::sendResponse(400, 'Could not perform action');
    }
}