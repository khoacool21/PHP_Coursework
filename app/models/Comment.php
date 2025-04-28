<?php
require_once __DIR__ . '/../config/database.php';

class Comment {
    private $pdo;

    public function __construct() {
        $this->pdo = connectDB();
    }

    public function create($post_id, $user_id, $content) {
        $sql = "INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$post_id, $user_id, $content]);
    }

    public function getById($id) {
        $sql = "SELECT c.*, u.username as author 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByPostId($post_id) {
        $sql = "SELECT c.*, u.username as author, u.id as user_id 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.post_id = ? 
                ORDER BY c.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($comment_id, $user_id, $content) {
        $sql = "UPDATE comments 
                SET content = ?, updated_at = NOW() 
                WHERE id = ? AND user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$content, $comment_id, $user_id]);
    }

    public function delete($comment_id, $user_id) {
        $sql = "DELETE FROM comments WHERE id = ? AND user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$comment_id, $user_id]);
    }

    public function getCommentCount($post_id) {
        $sql = "SELECT COUNT(*) as count FROM comments WHERE post_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$post_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function getRecentComments($limit = 5) {
        $sql = "SELECT c.*, u.username as author, p.title as post_title, p.id as post_id 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                JOIN posts p ON c.post_id = p.id 
                ORDER BY c.created_at DESC 
                LIMIT ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserComments($user_id) {
        $sql = "SELECT c.*, p.title as post_title, p.id as post_id 
                FROM comments c 
                JOIN posts p ON c.post_id = p.id 
                WHERE c.user_id = ? 
                ORDER BY c.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isCommentOwner($comment_id, $user_id) {
        $sql = "SELECT COUNT(*) as count 
                FROM comments 
                WHERE id = ? AND user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$comment_id, $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result['count'] ?? 0) > 0;
    }
} 