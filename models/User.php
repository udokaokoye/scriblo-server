<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

class User
{
    private $conn;
    private $table = 'users';

    // properties of a user
    public $id;
    public $name;
    public $email;
    public $avatar;
    public $interests;
    public $bio;
    public $createdAt;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getAllUsers()
    {
        // Code to fetch all users from the database

    }

    public function addUser($userData)
    {
        // Code to add a user to the database
        try {
            $query = 'INSERT INTO ' . $this->table . ' (name, email, avatar, interests, bio, createdAt) VALUES ( ?, ?, ?, ?, ?, ?)';
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$userData['name'], $userData['email'], $userData['avatar'], $userData['interests'], $userData['bio'], $userData['createdAt']]);
            if ($stmt) {
                // update username with id
                $query = 'UPDATE ' . $this->table . ' SET username = ? WHERE email = ?';
                $newstmt = $this->conn->prepare($query);
                $newstmt->execute([strtolower(str_replace(' ', '', $userData['name'])) . '_' . $this->conn->lastInsertId(), $userData['email']]);
                return $newstmt;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }

    public function getUser($email)
    {
        // Code to fetch a single user from the database
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return $user;
        } else {
            return false;
        }
            
    }

    public function updateUser($id, $userData)
    {
        // Code to update a user in the database
    }

    public function deleteUser($id)
    {
        // Code to delete a user from the database
    }

    public function checkIfUserExists($email)
    {
        // Code to check if a user exists in the database
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    public function followUser($userId, $userToFollowId, $date)
    {
        try {
            $query = 'INSERT INTO followers (follower_id, following_id, createdAt) VALUES (?, ?, ?)';
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$userId, $userToFollowId, $date]);
            return $stmt;
        } catch (PDOException $e) {
            // check if error is duplicate entry
            if ($e->getCode() == 23000) {
                $result = $this->unfollowUser($userId, $userToFollowId);
                return $result;
            } else {
                echo ResponseHandler::sendResponse(500, $e->getMessage());
                return;
            }
        }
    }

    public function unfollowUser($userId, $userToUnfollowId)
    {
        try {
            $query = 'DELETE FROM followers WHERE follower_id = ? AND following_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$userId, $userToUnfollowId]);
            return $stmt;
        } catch (PDOException $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }
}
