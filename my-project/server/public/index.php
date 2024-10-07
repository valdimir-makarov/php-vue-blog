<?php

session_start();
// Debugging outputs (can comment these out after checking)
var_dump(__DIR__ . '/../controllers/PostControllers.php');
var_dump(file_exists(__DIR__ . '/../controllers/PostControllers.php'));
var_dump(__DIR__ . '/../controllers/UserController.php');
var_dump(file_exists(__DIR__ . '/../controllers/UserController.php'));

require_once __DIR__ . '/../controllers/PostControllers.php';
require_once __DIR__ . '/../controllers/UserController.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Simple router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$controller = null;
// middlewares/AuthMiddleware.php

class AuthMiddleware {
    public static function checkAuthentication() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'User not authenticated.']);
            exit();
        }
    }
}
if (!in_array($uri, ['/login', '/register'])) {
    AuthMiddleware::checkAuthentication();
}


if ($uri === '/posts') {
    $controller = new PostController();
    $controller->index();
} elseif (preg_match('/^\/posts\/(\d+)$/', $uri, $matches)) {
    $controller = new PostController();
    $controller->show($matches[1]);
} elseif ($uri === '/posts/create') {
    if ($method === 'POST') {
        $controller = new PostController();
        $controller->create();
    } else {
        http_response_code(405);
        echo "Method Not Allowed";
    }
} 
elseif ($uri === '/posts/search' && $method === 'POST') { // Add the search route
    $controller = new PostController();
    $controller->searchPosts(); // Call the searchPosts method in PostController
}

elseif ($uri === '/register' && $method === 'POST') {
    $controller = new UserController();
    $controller->register();
} elseif ($uri === '/login' && $method === 'POST') {
    $controller = new UserController();
    $controller->login();
} elseif ($uri === '/logout' && $method === 'POST') {
    $controller = new UserController();
    $controller->logout();
}
 elseif ($uri === '/posts/comments' && $method === 'POST') {
    $controller = new PostController($db);
    $controller->addComment();
} elseif (preg_match('/^\/posts\/(\d+)\/comments$/', $uri, $matches) && $method === 'GET') {
    $controller = new PostController($db);
    $controller->getComments($matches[1]);
}

else {
    http_response_code(404);
    echo "404 Not Found";
}
?>
