<?php
require_once '../services/PostService.php';
require_once '../services/CommentService.php';

class PostController {
    private $postService;
    private $commentService;

    public function __construct() {
        $this->postService = new PostService();
        $this->commentService = new CommentService(); // Initialize CommentService
    }

    public function index() {
        header('Content-Type: application/json');
        $posts = $this->postService->getAllPosts();
        echo json_encode($posts);
    }

    public function show($id) {
        header('Content-Type: application/json');
        $post = $this->postService->getPostById($id);
        echo json_encode($post);
    }

    public function create() {
       
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if the user is logged in by verifying session
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401); // Unauthorized
                echo json_encode(['error' => 'User not logged in.']);
                return;
            }
    
            $user_id = $_SESSION['user_id']; // Retrieve user_id from session
    
            // Handle the image upload if an image is present
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['image'];
                $imageName = uniqid() . '-' . basename($image['name']); // Safely get the image name
                $targetDir = '../uploads/';
                $targetFile = $targetDir . $imageName;
    
                // Move the uploaded file to the target directory
                if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
                    echo json_encode(['error' => 'Failed to upload image.']);
                    return;
                }
            } else {
                echo json_encode(['error' => 'No image uploaded or error in file upload.']);
                return;
            }
    
            // Get title and content directly from the form data
            if (isset($_POST['title'], $_POST['content'])) {
                $title = $_POST['title'];
                $content = $_POST['content'];
    
                try {
                    // Pass the image name and user_id to the service layer
                    $result = $this->postService->createPost($title, $content, $user_id, $imageName);
                    if ($result) {
                        echo json_encode(['message' => 'Post created successfully.']);
                    } else {
                        echo json_encode(['error' => 'Failed to create post.']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['error' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['error' => 'Invalid input.']);
            }
        }
    }
    

    public function addComment() {
        session_start(); // Start session to get the logged-in user_id
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['post_id'], $data['content'])) {
                $postId = $data['post_id'];
                $content = $data['content'];

                // Check if the user is logged in
                if (isset($_SESSION['user_id'])) {
                    $userId = $_SESSION['user_id'];

                    try {
                        if ($this->commentService->addComment($postId, $userId, $content)) {
                            http_response_code(201);
                            echo json_encode(["message" => "Comment added successfully."]);
                        } else {
                            http_response_code(500);
                            echo json_encode(["error" => "Failed to add comment."]);
                        }
                    } catch (Exception $e) {
                        http_response_code(400);
                        echo json_encode(["error" => $e->getMessage()]);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(["error" => "User not logged in."]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Invalid input."]);
            }
        }
    }

    public function getComments($postId) {
        header('Content-Type: application/json');

        try {
            $comments = $this->commentService->getCommentsByPostId($postId);
            echo json_encode($comments);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function searchPosts() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON format.']);
            return;
        }

        $keyword = isset($data['keyword']) ? $data['keyword'] : null;

        if ($keyword === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Keyword is required for search.']);
            return;
        }

        try {
            $posts = $this->postService->searchPosts($keyword);
            http_response_code(200);
            echo json_encode($posts);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
