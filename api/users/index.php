<?php
//  ini_set('display_errors', 1); 
//  ini_set('display_startup_errors', 1); 
//  error_reporting(E_ALL);

include_once '../../config/database.php';
include_once '../../models/User.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/JwtUtility.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    JwtUtility::verifyHttpAuthorization();

    $database = new Database();
    $db = $database->connect();

    $user = new User($db);
    $userResult = $user->getAllUsers();
    if ($userResult == null) {
        echo ResponseHandler::sendResponse(500, 'User could not be retrieved');
        return;
    }

    echo json_encode($userResult);
} else if ($method == 'POST') {
    $database = new Database();
    $db = $database->connect();

    $user = new User($db);
    $useralreadyExists = $user->checkIfUserExists($_POST['email']);
    if ($useralreadyExists) {
        echo ResponseHandler::sendResponse(400, 'User already exists');
        return;
    }
    $userAdded = $user->addUser($_POST);

    if (!$userAdded) {
        echo ResponseHandler::sendResponse(500, 'User could not be added');
        return;
    }
    $token = JwtUtility::generateToken(['email' => $_POST['email']], '+5 minutes');
    echo ResponseHandler::sendResponse(200, 'User added successfully', $token=$token);
}else {
    echo json_encode(['error' => 'Method not allowed']);
}