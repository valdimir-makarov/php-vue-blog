<?php
require_once __DIR__ . '/../config/Database.php';
class PostModel {
    private $db;

    public function __construct() {
        $database = new Database(); // Create an instance of Database class
        $this->db = $database->connect(); // Store the PDO object in $this->db
    }

    public function getAllPosts() {
        $stmt = $this->db->query("SELECT * FROM posts");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostById($id) {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPost($title, $content, $user_id, $image) {
        $stmt = $this->db->prepare("INSERT INTO posts (title, content, user_id, image) VALUES (:title, :content, :user_id, :image)");
        return $stmt->execute([
            'title' => $title, 
            'content' => $content, 
            'user_id' => $user_id, 
            'image' => $image // Save the image file name/path
        ]);
    }
    
    public function searchPosts($keyword) {
        // Prepare the SQL query with wildcard search for title or content
        $query = "
            SELECT *,
                CASE
                    WHEN title = :exactMatch THEN 1
                    WHEN title LIKE :partialMatch THEN 2
                    ELSE 3
                END AS match_priority
            FROM posts
            WHERE title LIKE :partialMatch OR content LIKE :partialMatch
            ORDER BY match_priority, title ASC";
    
        $stmt = $this->db->prepare($query);
        $exactMatch = $keyword;
        $partialMatch = '%' . $keyword . '%';
        $stmt->bindParam(':exactMatch', $exactMatch, PDO::PARAM_STR);
        $stmt->bindParam(':partialMatch', $partialMatch, PDO::PARAM_STR);
    
        // Execute the query
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all matching posts
        } else {
            throw new Exception("Unable to search posts.");
        }
    }
    
    
}
?>
