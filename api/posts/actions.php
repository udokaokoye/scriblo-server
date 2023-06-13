<?php
include_once '../../config/database.php';
include_once '../../models/Post.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/JwtUtility.php';



$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    $database = new Database();
    $db = $database->connect();

    $post = new Post($db);

    if (!isset($_POST['action'])) {
        echo ResponseHandler::sendResponse(400, 'action is required');
        return;
    }
    $result = null;

    $action = $_POST['action'];

    if ($action == 'likepost') {
        $result = $post->likePost($_POST['postId'], $_POST['userId'], $_POST['date']);
    }
     if($action == 'commentpost') {
        if ($_POST['replyId'] == '') {
            $_POST['replyId'] = null;
        }
        $result = $post->commentPost($_POST['postId'], $_POST['userId'], $_POST['comment'], $_POST['date'], $_POST['replyId']);
    } 
    if ($action == 'bookmarkpost') {
        $result = $post->bookmarkPost($_POST['postId'], $_POST['userId'], $_POST['date']);
    }

    if ($result) {
        echo ResponseHandler::sendResponse(200, 'Action Performed');
    } else {
        echo ResponseHandler::sendResponse(400, 'Could not perform action');
    }
    

} else 
if ($method == 'GET') {
    if (!isset($_GET['data'])) {
        echo ResponseHandler::sendResponse(400, 'Data is required');
        return;
    }

    $database = new Database();
    $db = $database->connect();
    $post = new Post($db);
    $dataToGet = $_GET['data'];
    $result = null;

    if ($dataToGet == 'comments') {
        $postId = $_GET['postId'];
        $result = $post->getCommnets($postId);
    }

    if ($dataToGet == 'likes') {
        $postId = $_GET['postId'];
        $result = $post->getLikes($postId);
    }

    if ($dataToGet == 'bookmarks') {
        $userId = $_GET['userId'];
        $result = $post->getBookmarks($userId);
    }

    if ($result) {
        echo ResponseHandler::sendResponse(200, null, $result);
    } else {
        echo ResponseHandler::sendResponse(200, 'No data found');
    }
} else if($method == 'DELETE') {
    if (!isset($_GET['data']) || !isset($_GET['id'])) {
        echo ResponseHandler::sendResponse(400, 'Data and Id is required');
        return;
    }


    $database = new Database();
    $db = $database->connect();
    $post = new Post($db);
    $result = null;

    $dataToDelete = $_GET['data'];
    $idToDelete = $_GET['id'];

    if ($dataToDelete == 'comment') {
        $result = $post->deleteComment($idToDelete);
    }
    if ($result) {
        echo ResponseHandler::sendResponse(200, 'Delete Successful');
    } else {
        echo ResponseHandler::sendResponse(400, 'Could not delete data');
        return;
    }
} else {
    echo ResponseHandler::sendResponse(400, 'Invalid request');
}