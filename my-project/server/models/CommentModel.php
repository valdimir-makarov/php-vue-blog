<?php
require_once __DIR__ . '/../config/Database.php';
class CommentModel {
    private $db;

    public function __construct() {
        $database = new Database(); // Create an instance of Database class
        $this->db = $database->connect(); // Store the PDO object in $this->db
    }

    public function createComment($postId, $userId, $content) {
        $stmt = $this->db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        return $stmt->execute([$postId, $userId, $content]);
    }

    public function getCommentsByPostId($postId) {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
