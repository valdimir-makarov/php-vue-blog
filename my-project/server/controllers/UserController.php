<?php
require_once '../services/UserService.php';

class UserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function register() {
        header('Content-Type: application/json');

        // Read the incoming JSON data
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if JSON decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON format.']);
            return;
        }

        // Access the values safely
        $username = isset($data['username']) ? $data['username'] : null;
        $email = isset($data['email']) ? $data['email'] : null;
        $password = isset($data['password']) ? $data['password'] : null;
        $confirmPassword = isset($data['confirm_password']) ? $data['confirm_password'] : null;

//confirm password-
        // Ensure all fields are provided
        if ($username === null || $email === null || $password === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'All fields are required.']);
            return;
        }
        if($confirmPassword !== $password){
              echo json_encode(["error"=>"password and confirm didnt math"]);
              return;
        }

        // Attempt to create the user
        try {
            if ($this->userService->createUser($username, $email, $password)) {
                http_response_code(201); // Created
                echo json_encode(['message' => 'User created successfully.']);
              
            } else {
                http_response_code(409); // Conflict
                echo json_encode(['error' => 'User already exists or could not be created.']);
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function login() {
        header('Content-Type: application/json');

        // Read the incoming JSON data
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if JSON decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON format.']);
            return;
        }

        // Access the values safely
        $email = isset($data['email']) ? $data['email'] : null;
        $password = isset($data['password']) ? $data['password'] : null;

        // Ensure both fields are provided
        if ($email === null || $password === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Email and password are required.']);
            return;
        }

        // Attempt to log in
        $user = $this->userService->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
          
        $_SESSION['username'] = $user['username'];

            http_response_code(200); // OK
            echo json_encode(['message' => 'Login successful.']);
            // Optionally redirect to posts or return a success message
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'Invalid email or password.']);
        }
    }
   
    

    public function logout() {
        session_destroy();
        header("Location: /login");
    }
}
?>
