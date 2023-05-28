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
            return $stmt;
        } catch (PDOException $e) {
            echo ResponseHandler::sendResponse(500, $e->getMessage());
            return;
        }
    }

    public function getUser($id)
    {
        // Code to fetch a single user from the database
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
}
