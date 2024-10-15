<?php
class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Function to get user profile by user_id
    public function getProfile($user_id)
    {
        $query = "SELECT user_id, CONCAT(first_name, ' ', last_name) as name, email, role FROM users WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
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

    // Function to assign a role to a user
    public function assignRole($user_id, $role)
    {
        // Check if the role is valid
        $valid_roles = ['admin', 'customer', 'vendor']; // Valid roles
        if (!in_array($role, $valid_roles)) {
            return ['status' => 'error', 'message' => 'Invalid role specified'];
        }

        // Update the role in the database
        $query = "UPDATE users SET role = :role WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Role assigned successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to assign role'];
        }
    }

    // function to revoke role from a user
    public function revokeRole($userId)
    {
        try {
            // Check if the user exists
            $checkQuery = 'SELECT role FROM users WHERE user_id = :user_id';
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':user_id', $userId);
            $checkStmt->execute();
            $user = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ['success' => false, 'message' => 'User not found.'];
            }

            // Revoke role (set to NULL or set a default role)
            $updateQuery = 'UPDATE users SET role = NULL WHERE user_id = :user_id';
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':user_id', $userId);
            $updateResult = $updateStmt->execute();

            if ($updateResult) {
                return ['success' => true, 'message' => 'User role revoked successfully.'];
            } else {
                return ['success' => false, 'message' => 'Failed to revoke user role.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
