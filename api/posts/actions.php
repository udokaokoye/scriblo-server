<?php

include_once '../../config/database.php';
include_once '../../models/Post.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/JwtUtility.php';
// include_once './utils/Crypt.php';
include_once __DIR__.'/../../utils/Crypt.php';

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

    if ($action == 'deletecomment') {
        $result = $post->deleteComment($_POST['commentId']);
    }

    if ($action == 'updatePost') {
        JwtUtility::verifyHttpAuthorization($_POST['authToken'] ?? null);
        if (!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['authorId'])) {
                echo ResponseHandler::sendResponse(400, 'Id, title, content and author are required');
                return;
            }
    
            $result = $post->updatePost($_POST);
    }

    if ($action == 'deleteBookmark') {
        $result = $post->deleteBookmark($_POST['bookmarkId']);
    }

    if ($action == 'deletePost') {
        JwtUtility::verifyHttpAuthorization($_POST['authToken'] ?? null);
        $result = $post->deletePost($_POST['postId']);
    }




    if ($result) {
        echo ResponseHandler::sendResponse(200, 'Action Performed');
    } else {
        echo ResponseHandler::sendResponse(400, 'Could not perform action');
    }

}

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

    if ($dataToGet == 'posts_username') {
        $username = $_GET['username'];
        $rest= $post->getUserPosts('username', $username);

        for ($i=0; $i < count($rest); $i++) { 
            $rest[$i]['previewCode'] !== null && $rest[$i]['previewCode'] = CryptHelper::decrypt($rest[$i]['previewCode']);
        }
        // while ($res = $rest) {
        //    $res['previewCode'] = CryptHelper::decrypt($res['previewCode']);
        //    array_push($dataToSend, $res);
        // }

        $result = $rest;
    }
    if ($dataToGet == 'checkIfCodeRequiredForPreview') {
        $result = $post->checkIfCodeRequiredForPreview($_GET['slug']);
     }

     if ($dataToGet == 'verifyCode') {
       $code = $post->getPreviewCode($_GET['slug'])[0];
       $hashedCode = $code['code'];
       if (CryptHelper::decrypt($hashedCode) == $_GET['code']) {
        // $slug = $code['username'] . '-' . $code['postId'];
        $result = $post->getPostById($code['postId']);
       } else {
        echo ResponseHandler::sendResponse(401, "Not Verified");
        return;
       }
     }

    if ($result) {
        echo ResponseHandler::sendResponse(200, null, $result);
    } else {
        echo ResponseHandler::sendResponse(200, 'No data found');
    }
} 
