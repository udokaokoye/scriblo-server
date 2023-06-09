<?php

include_once '../../config/database.php';
include_once '../../models/Post.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/JwtUtility.php';



$method = $_SERVER['REQUEST_METHOD'];


if ($method == 'POST') {
    JwtUtility::verifyHttpAuthorization();
    // ResponseHandler::sendResponse(200, 'Post added');
    // return;

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
        echo ResponseHandler::sendResponse(200, 'Post added');
    } else {
        echo ResponseHandler::sendResponse(400, 'Post not added');
    }
} 
if($method == 'GET') {
    // JwtUtility::verifyHttpAuthorization();
    $database = new Database();
    $db = $database->connect();

    $post = new Post($db);
    $posts = null;

    if (isset($_GET['categories'])) {
        $posts = $post->getPosts($_GET['categories']);
    } 
    // else {
    //     $posts = $post->getPosts(null);
    // }

    if (isset($_GET['search']) && isset($_GET['class'])) {
        $posts = $post->searchPosts($_GET['search'], $_GET['class']);
    } 
    

    if ($posts) {
        echo ResponseHandler::sendResponse(200, null, $posts);
    } else {
        echo ResponseHandler::sendResponse(200, 'No posts found');
    }
} 
if($method == 'DELETE') {
    JwtUtility::verifyHttpAuthorization();

    $database = new Database();
    $db = $database->connect();

    $post = new Post($db);

    if (!isset($_GET['id'])) {
        echo ResponseHandler::sendResponse(400, 'Id is required');
        return;
    }

    $postDeleted = $post->deletePost($_GET['id']);

    if ($postDeleted) {
        echo ResponseHandler::sendResponse(200, 'Post deleted');
    } else {
        echo ResponseHandler::sendResponse(400, 'Post not deleted');
    }
} 
if($method == 'PATCH') {
    JwtUtility::verifyHttpAuthorization();

    $database = new Database();
    $db = $database->connect();

    $post = new Post($db);

    if (!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['authorId']) || !isset($_POST['slug'])) {
        echo ResponseHandler::sendResponse(400, 'Id, title, slug, content and author are required');
        return;
    }

    $postUpdated = $post->updatePost($_POST);

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