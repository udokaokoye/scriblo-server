<?php
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
echo json_encode(uniqid());