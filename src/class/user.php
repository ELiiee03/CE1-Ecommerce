<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Function to get user profile by user_id
    public function getProfile($user_id) {
        $query = "SELECT user_id, CONCAT(first_name, ' ', last_name) as name, email, role FROM users WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);

        // Use PDO's bindParam to bind the user_id to the placeholder
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the user data
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                'id' => $result['user_id'],
                'name' => $result['name'],
                'email' => $result['email'],
                'roles' => [$result['role']]
            ];
        }

        return null; // User not found
    }
}
