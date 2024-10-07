<?php
require_once __DIR__ . '/../config/Database.php';

class UserModel {
    private $db;

    public function __construct() {
        $database = new Database(); // Create an instance of Database class
        $this->db = $database->connect(); // Store the PDO object in $this->db
    }

    // Create User with basic validation
    public function createUser($username, $email, $password) {
        if ($this->getUserByEmail($email)) {
            throw new Exception("Email already exists.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, created_at, updated_at) VALUES (:username, :email, :password, NOW(), NOW())");

        if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword])) {
            return $this->db->lastInsertId(); // Return new user ID on success
        }

        return false;
    }

    // Get User by Email
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update User with optional password update
    public function updateUser($id, $username, $email, $password = null) {
        $set = "username = :username, email = :email, updated_at = NOW()";
        $params = ['id' => $id, 'username' => $username, 'email' => $email];

        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $set .= ", password = :password";
            $params['password'] = $hashedPassword;
        }

        $stmt = $this->db->prepare("UPDATE users SET $set WHERE id = :id");
        return $stmt->execute($params);
    }

    // Delete User
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Check if email is valid
    private function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Additional method: Get user by ID
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
