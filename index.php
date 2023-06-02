<?php
include_once './config/database.php';
include_once './utils/JwtUtility.php';
include_once './utils/ResponseHandler.php';
// $requestUri = $_SERVER['REQUEST_URI'];
// $requestMethod = $_SERVER['REQUEST_METHOD'];

// if ($requestUri === '/user' && $requestMethod === 'GET') {
//     $controller = new \App\Controllers\UserController();
//     echo $controller->getUser($_GET['id']);
// } elseif ($requestUri === '/user' && $requestMethod === 'POST') {
//     $controller = new \App\Controllers\UserController();
//     echo $controller->addUser($_POST);
// } else {
//     echo json_encode(['error' => 'Route not found']);
// }
// JwtUtility::verifyHttpAuthorization();
echo ResponseHandler::sendResponse(200, 'User retrieved successfully');