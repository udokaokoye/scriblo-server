<?php
include_once '../../config/database.php';
include_once '../../models/User.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/JwtUtility.php';



$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    if (!isset($_POST['action'])) {
        echo ResponseHandler::sendResponse(400, 'action is required');
        return;
    }
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $result = null;
    $action = $_POST['action'];

    if ($action == 'followuser') {
        $result = $user->followUser($_POST['userId'], $_POST['followId'], $_POST['date']);
    }

    if ($action == 'unfollowuser') {
        $result = $user->unfollowUser($_POST['userId'], $_POST['unfollowId']);
    }

    if ($result) {
        echo ResponseHandler::sendResponse(200, $result);
    } else {
        echo ResponseHandler::sendResponse(400, 'Could not perform action');
    }
} 

if ($method == 'GET') {
    if (!isset($_GET['action']) || $_GET['action'] == '') {
        echo ResponseHandler::sendResponse(400, 'Action is required');
        return;
    }

    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $action = $_GET['action'];
    $result = null;

    if ($action == 'getUser') {
        $result = $user->getUserWithUsername($_GET['username']);
    }

    if ($action == 'getUserFollows') {
        $following = [];
        $followers = [];
        $data = $user->getFollowing($_GET['userId']);

        foreach ($data as $dt) {
            $userID = $dt['id'];
            $relationship = $dt['relationship'];
            $userDetails = array_slice($dt, 3); // Exclude the user_id and relationship fields
        
            if ($relationship === 'following') {
                $followers[] = [
                    'user_id' => $userID,
                    'user_details' => $userDetails
                ];
            } elseif ($relationship === 'follower') {
                $following[] = [
                    'user_id' => $userID,
                    'user_details' => $userDetails
                ];
            }
        }
        $result = [
            'followers' => $followers,
            'followings' => $following,
        ];
    }


    if ($result) {
        echo ResponseHandler::sendResponse(200, '', $result);
    } else {
        echo ResponseHandler::sendResponse(400, 'Could not perform action');
    }
}