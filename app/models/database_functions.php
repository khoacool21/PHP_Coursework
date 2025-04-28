<?php
require_once __DIR__ . '/../config/database.php';

class Module {
    // Get all modules
    public function getAllModules() {
        $pdo = connectDB();
        $stmt = $pdo->query("SELECT * FROM modules ORDER BY name");
        return $stmt->fetchAll();
    }
    
    // Get module by ID
    public function getModuleById($id) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Create module
    public function createModule($name) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("INSERT INTO modules (name) VALUES (?)");
        return $stmt->execute([$name]);
    }
    
    // Update module
    public function updateModule($id, $name) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("UPDATE modules SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }
    
    // Delete module
    public function deleteModule($id) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("DELETE FROM modules WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

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
}

class User {
    // Get all users
    public function getAll() {
        $pdo = connectDB();
        $stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY username");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get user by ID
    public function getUserById($id) {
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Create user
    public function create($username, $email, $password, $role = 'user') {
        $pdo = connectDB();
        
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            return false;
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hashed_password, $role]);
    }
    
    // Update user
    public function update($id, $username, $email, $role = 'user', $password = null) {
        $pdo = connectDB();
        
        // Check if username or email already exists for other users
        $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $id]);
        if ($stmt->fetch()) {
            return false;
        }
        
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?");
            return $stmt->execute([$username, $email, $hashed_password, $role, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
            return $stmt->execute([$username, $email, $role, $id]);
        }
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
        $stmt = $pdo->prepare("SELECT id, username, email, role, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Remove password from returned user data
            unset($user['password']);
            return $user;
        }
        return false;
    }
}
?> 