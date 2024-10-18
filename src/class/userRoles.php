<?php
class UserRoles {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUserRoles($user_id) {
        $query = "SELECT role FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $roles = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = $row['role'];
        }
        return $roles;
    }

    public function getUserProfile($user_id) {
        $query = "SELECT role FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
