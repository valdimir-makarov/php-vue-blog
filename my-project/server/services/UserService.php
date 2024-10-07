<?php
require_once '../models/UserModel.php';

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function createUser($username, $email, $password) {
        return $this->userModel->createUser($username, $email, $password);
    }

    public function getUserByEmail($email) {
        return $this->userModel->getUserByEmail($email);
    }
}
?>
