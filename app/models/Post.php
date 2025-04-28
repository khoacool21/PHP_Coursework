<?php
require_once __DIR__ . '/../config/database.php';

// Module class is already defined in database_functions.php
// Removing duplicate declaration

class Post {
    // Get all posts
    public function getAllPosts() {
        $pdo = connectDB();
        $stmt = $pdo->query("SELECT posts.*, users.username AS author, modules.name AS module FROM posts 
                         JOIN users ON posts.user_id = users.id
                         JOIN modules ON posts.module_id = modules.id
                         ORDER BY posts.created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get single post
    public function getPostById($id) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT posts.*, users.username AS author, modules.name AS module FROM posts 
                             JOIN users ON posts.user_id = users.id
                             JOIN modules ON posts.module_id = modules.id
                             WHERE posts.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Create post
    public function createPost($title, $content, $user_id, $module_id, $image = null) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id, module_id, image, created_at) 
                              VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$title, $content, $user_id, $module_id, $image]);
    }
    
    // Update post
    public function updatePost($id, $title, $content, $module_id, $image = null) {
        $pdo = connectDB();
        if ($image) {
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, module_id = ?, image = ? WHERE id = ?");
            return $stmt->execute([$title, $content, $module_id, $image, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, module_id = ? WHERE id = ?");
            return $stmt->execute([$title, $content, $module_id, $id]);
        }
    }
    
    // Delete post
    public function deletePost($id) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Get posts by user
    public function getPostsByUser($user_id) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT posts.*, users.username AS author, modules.name AS module FROM posts 
                             JOIN users ON posts.user_id = users.id
                             JOIN modules ON posts.module_id = modules.id
                             WHERE posts.user_id = ?
                             ORDER BY posts.created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get posts by module
    public function getPostsByModule($module_id) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT posts.*, users.username AS author, modules.name AS module FROM posts 
                             JOIN users ON posts.user_id = users.id
                             JOIN modules ON posts.module_id = modules.id
                             WHERE posts.module_id = ?
                             ORDER BY posts.created_at DESC");
        $stmt->execute([$module_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Search posts by title or content
    public function searchPosts($query) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT posts.*, users.username AS author, modules.name AS module FROM posts 
                             JOIN users ON posts.user_id = users.id
                             JOIN modules ON posts.module_id = modules.id
                             WHERE posts.title LIKE ? OR posts.content LIKE ?
                             ORDER BY posts.created_at DESC");
        $likeQuery = '%' . $query . '%';
        $stmt->execute([$likeQuery, $likeQuery]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class User {
    // Get all users
    public function getAll() {
        $pdo = connectDB();
        $stmt = $pdo->query("SELECT * FROM users ORDER BY username");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get user by ID
    public function getUserById($id) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Create user
    public function create($username, $email, $password, $role = 'user') {
        $pdo = connectDB();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hashed_password, $role]);
    }
    
    // Update user
    public function update($id, $username, $email, $role = 'user') {
        $pdo = connectDB();
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $role, $id]);
    }
    
    // Delete user
    public function delete($id) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Authenticate user
    public function authenticate($username, $password) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>
