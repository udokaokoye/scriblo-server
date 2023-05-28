<?php
//  ini_set('display_errors', 1); 
//  ini_set('display_startup_errors', 1); 
//  error_reporting(E_ALL);

include_once '../../config/database.php';
include_once '../../models/User.php';
include_once '../../utils/ResponseHandler.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $database = new Database();
    $db = $database->connect();

    $user = new User($db);
    $users = $user->getAllUsers();

    echo json_encode($users);
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
    echo ResponseHandler::sendResponse(200, 'User added successfully');
}else {
    echo json_encode(['error' => 'Method not allowed']);
}