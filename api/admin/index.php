<?php
include_once '../../config/database.php';
include_once '../../models/Admin.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/JwtUtility.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET") {
    if (!isset($_GET['action'])) {
        echo ResponseHandler::sendResponse(400, 'Action is required');
        return;
    }
    $action = $_GET['action'];

    $database = new Database();
    $db = $database->connect();
    $admin = new Admin($db);

    $result = null;

    if ($action == 'getFeedbacks') {
        $result = $admin->getFeedbacks();
    }

    if ($action == 'getUsersCount') {
        $result = $admin->getUsersCount();
    }

    if ($action == 'solve' && isset($_GET['feedbackID'])) {
        $result = $admin->solveFeedback($_GET['feedbackID']);
    }

    if ($action == 'unsolve' && isset($_GET['feedbackID'])) {
        $result = $admin->unsolveFeedback($_GET['feedbackID']);
    }

    if ($action == 'getVerficationRequest') {
       $result = $admin->getVerificationRequests();
    }

    if ($action == 'getArticlesCount') {
        $result = $admin->getArticleCount();
    }

    if ($result) {
        echo ResponseHandler::sendResponse(200, 'Action performed successfully', $result);
    } else {
        echo ResponseHandler::sendResponse(400, 'Could not perform action');
    }



} else {
    echo ResponseHandler::sendResponse(405, 'Method not allowed');
}
