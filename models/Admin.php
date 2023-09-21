<?php

class Admin {
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getFeedbacks () {
        $query = "SELECT * FROM feedbacks ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


    public function getUsersCount () {
        $query = "SELECT COUNT(*) AS total_records FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticleCount () {
        $query = "SELECT COUNT(*) AS total_records FROM posts WHERE isHidden = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVerificationRequests () {
        $query = "SELECT * FROM verification_requests ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function solveFeedback ($feedbackID) {
        $query = "UPDATE feedbacks SET solved = 'true' WHERE id = '$feedbackID'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if ($stmt) {
            return true;
        } else {
            return false;
        }

    }

    public function unsolveFeedback ($feedbackID) {
        $query = "UPDATE feedbacks SET solved = 'false' WHERE id = '$feedbackID'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if ($stmt) {
            return true;
        } else {
            return false;
        }

    }
}