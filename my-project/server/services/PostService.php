<?php
require_once __DIR__ . '/../models/PostModels.php';


class PostService {
    private $postModel;

    public function __construct() {
        $this->postModel = new PostModel();
    }

    public function getAllPosts() {
        return $this->postModel->getAllPosts();
    }

    public function getPostById($id) {
        $result= $this->postModel->getPostById($id);
        echo json_encode($result);
    }

    public function createPost($title, $content, $user_id, $image) {
        return $this->postModel->createPost($title, $content, $user_id, $image);
    }
    
    public function searchPosts($keyword) {
        // Call the search method from the UserModel
        return $this->postModel->searchPosts($keyword);
    }
}
?>
