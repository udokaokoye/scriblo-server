<?php

include_once '../../config/database.php';
include_once '../../models/Post.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/JwtUtility.php';
include_once '../../utils/Algorithim.php';




$method = $_SERVER['REQUEST_METHOD'];


// JwtUtility::verifyHttpAuthorization();
if ($method == 'POST') {
    JwtUtility::verifyHttpAuthorization($_POST['authToken'] ?? null);

    $database = new Database();
    $db = $database->connect();

    $post = new Post($db);

    // echo ResponseHandler::sendResponse(200, $_POST['mediaFiles']);
    // return;

    if (!isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['authorId']) || !isset($_POST['slug'])) {
        echo ResponseHandler::sendResponse(400, 'Title, content, slug and author are required');
        return;
    }

    $postAdded = $post->addPost($_POST);

    if ($postAdded) {
        echo ResponseHandler::sendResponse(200, $postAdded);
    } else {
        echo ResponseHandler::sendResponse(400, $postAdded);
    }
}
if ($method == 'GET') {
    // JwtUtility::verifyHttpAuthorization();
    $database = new Database();
    $db = $database->connect();

    $post = new Post($db);
    $posts = null;

    if (isset($_GET['categories'])) {
        $posts = $post->getPosts($_GET['categories']);
        $relevantPosts = FeedAlgorithim::rankPosts($posts);
    }
    if (isset($_GET['search']) && isset($_GET['class'])) {
        $posts = $post->searchPosts($_GET['search'], $_GET['class']);
    }

    if (isset($_GET['slug'])) {
        $posts = $post->getPost($_GET['slug']);
    }

    if (isset($_GET['articleId'])) {
        $posts = $post->getPostById($_GET['articleId']);
    }


    if ($posts) {
        echo ResponseHandler::sendResponse(200, null, $posts);
    } else {
        echo ResponseHandler::sendResponse(200, 'No posts found');
    }
}
// if($method == 'DELETE') {
//     JwtUtility::verifyHttpAuthorization();

//     $database = new Database();
//     $db = $database->connect();

//     $post = new Post($db);

//     if (!isset($_GET['id'])) {
//         echo ResponseHandler::sendResponse(400, 'Id is required');
//         return;
//     }

//     $postDeleted = $post->deletePost($_GET['id']);

//     if ($postDeleted) {
//         echo ResponseHandler::sendResponse(200, 'Post deleted');
//     } else {
//         echo ResponseHandler::sendResponse(400, 'Post not deleted');
//     }
// } 
if ($method == 'PATCH') {
    JwtUtility::verifyHttpAuthorization($_POST['authToken'] ?? null);

    $database = new Database();
    $db = $database->connect();

    $post = new Post($db);


        if (!isset($jsonData['id']) || !isset($jsonData['title']) || !isset($jsonData['content']) || !isset($jsonData['authorId'])) {
            echo ResponseHandler::sendResponse(400, 'Id, title, content and author are required');
            return;
        }

        $postUpdated = $post->updatePost($jsonData);

        if ($postUpdated) {
            echo ResponseHandler::sendResponse(200, 'Post updated');
        } else {
            echo ResponseHandler::sendResponse(400, 'Post not updated');
        }

} 

// echo ResponseHandler::sendResponse(405, 'Method not allowed');

// else if($method == "PATCH") {
//     // JwtUtility::verifyHttpAuthorization();
//     $database = new Database();
//     $db = $database->connect();

//     $post = new Post($db);

//     if (!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['authorId']) || !isset($_POST['slug'])) {
//         echo ResponseHandler::sendResponse(400, 'Id, title, slug, content and author are required');
//         return;
//     }

//     $postUpdated = $post->updatePost($_POST);

//     if ($postUpdated) {
//         echo ResponseHandler::sendResponse(200, 'Post updated');
//     } else {
//         echo ResponseHandler::sendResponse(400, 'Post not updated');
//     }
// } else {
//     echo ResponseHandler::sendResponse(405, 'Method not allowed');
// }