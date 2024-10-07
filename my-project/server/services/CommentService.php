<?php

require_once __DIR__ . '/../models/CommentModel.php';

class CommentService {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new CommentModel();
    }

    public function addComment($postId, $userId, $content) {
        // Business logic, e.g. validation, sanitization, etc.
        if (empty($content)) {
            throw new Exception("Comment content cannot be empty.");
        }

        return $this->commentModel->createComment($postId, $userId, $content);
    }

    public function getCommentsByPostId($postId) {
        return $this->commentModel->getCommentsByPostId($postId);
    }
}
