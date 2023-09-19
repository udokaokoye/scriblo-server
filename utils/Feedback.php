<?php
include '../config/database.php';
include_once './ResponseHandler.php';
$method = $_SERVER['REQUEST_METHOD'];


if ($method == 'POST') {
    $database = new Database();
    $db = $database->connect();
    if (!isset($_POST['type']) || !isset($_POST['page']) || !isset($_POST['problem']) || !isset($_POST['reproduction']) || !isset($_POST['date'])) {
        echo ResponseHandler::sendResponse(400, "Feedback Type, Page, Problem, Reproduction and Date is required.");
        return;
    }
    $feedbackType = $_POST['type'];
    $feedbackPage = $_POST['page'];
    $feedbackProblem = $_POST['problem'];
    $feedbackReproduction = $_POST['reproduction'];
    $feedbackDate = $_POST['date'];
    $feedbackEmail = $_POST['email'];



    $query = "INSERT INTO feedbacks (`email`, `type`, `page`, `problem`, `reproduction`, `created_at`) VALUES (?, ?, ?, ?, ? ,?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$feedbackEmail, $feedbackType, $feedbackPage, $feedbackProblem, $feedbackReproduction, $feedbackDate]);

    if ($stmt) {
        echo ResponseHandler::sendResponse(200, "Feedback received");
    } else {
        echo ResponseHandler::sendResponse(500, "Request Failed");
        return;
    }
} else {
    echo ResponseHandler::sendResponse(400, "Invaild Request");
}