<?php
include_once './config/database.php';
include_once './utils/JwtUtility.php';
include_once './utils/ResponseHandler.php';
include_once './utils/Crypt.php';
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

$originalData = "Hello, this is a secret message!";
$encryptionKey = "YourEncryptionKey"; // Replace this with a secure encryption key

// Encrypt the data
$encryptedData = Crypt::encrypt($originalData);

// Decrypt the data
$decryptedData = Crypt::decrypt($encryptedData);

// Output results
echo "Original Data: " . $originalData . "<br>";
echo "Encrypted Data: " . $encryptedData . "<br>";
echo "Decrypted Data: " . $decryptedData . "<br>";