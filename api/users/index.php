<?php
//  ini_set('display_errors', 1); 
//  ini_set('display_startup_errors', 1); 
//  error_reporting(E_ALL);

include_once '../../config/database.php';
include_once '../../models/User.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/JwtUtility.php';
include_once '../../utils/sendWelcomeEmail.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // JwtUtility::verifyHttpAuthorization();

    if (!isset($_GET['email'])) {
        echo ResponseHandler::sendResponse(400, 'Email not provided');
        return;
    }

    if(isset($_GET['with']) && $_GET['with'] == 'token') {
        $database = new Database();
        $db = $database->connect();

        $user = new User($db);
        $userResult = $user->getUser($_GET['email']);
        if ($userResult == null) {
            echo ResponseHandler::sendResponse(404, 'User not found');
            return;
        }
        $userResult['token'] = JwtUtility::generateToken(["email" => $_GET["email"]], "+30 days");

        echo ResponseHandler::sendResponse(200, 'User retrieved successfully', $userResult);
        return;
    }

    $database = new Database();
    $db = $database->connect();

    $user = new User($db);
    $userResult = $user->getUser($_GET['email']);
    if ($userResult == null) {
        echo ResponseHandler::sendResponse(404, 'User not found');
        return;
    }

    echo ResponseHandler::sendResponse(200, 'User retrieved successfully', $userResult);
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
    $token = JwtUtility::generateToken(["email" => $_POST["email"]], "+5 minutes");
    echo ResponseHandler::sendResponse(200, 'User added successfully', null, $token);
    sendWelcomeEmail($db, $_POST["email"]);
}else {
    echo json_encode(['error' => 'Method not allowed']);
}