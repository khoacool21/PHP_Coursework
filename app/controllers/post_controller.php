<?php
session_start();
require_once __DIR__ . '/../models/database_functions.php';


// Get requested action from either POST or GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create_post':
        createPost();
        break;
    case 'edit_post':
        editPost();
        break;
    case 'delete_post':
        deletePost();
        break;
    default:
        // Redirect to home if no valid action
        header('Location: /Coursework/index.php');
        exit;
}

function createPost() {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'You must be logged in to post a question';
        header('Location: /Coursework/app/views/login.php');
        exit;
    }
    
    // Validate input
    if (empty($_POST['title']) || empty($_POST['content']) || empty($_POST['module_id'])) {
        $_SESSION['error'] = 'Title, content and module are required';
        // Save form data for repopulation
        $_SESSION['form_data'] = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'module_id' => $_POST['module_id'] ?? ''
        ];
        header('Location: /Coursework/index.php?action=create_post');
        exit;
    }
    
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $module_id = (int)$_POST['module_id'];
    $user_id = $_SESSION['user_id'];
    $image = null;
    
    // Save form data in case upload fails
    $_SESSION['form_data'] = [
        'title' => $title,
        'content' => $content,
        'module_id' => $module_id
    ];
    
    // Handle image upload if present
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        $filesize = $_FILES['image']['size'];
        
        // Validate file extension
        if (!in_array(strtolower($filetype), $allowed)) {
            $_SESSION['error'] = 'Only JPG, JPEG, PNG, and GIF files are allowed';
            header('Location: /Coursework/index.php?action=create_post');
            exit;
        }
        
        // Validate file size (limit to 2MB)
        $max_size = 2 * 1024 * 1024; // 2MB in bytes
        if ($filesize > $max_size) {
            $_SESSION['error'] = 'Image file size must be less than 2MB';
            header('Location: /Coursework/index.php?action=create_post');
            exit;
        }
        
        // Create upload directory if it doesn't exist
        $upload_dir = '../../public/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generate unique filename
        $new_filename = uniqid() . '.' . $filetype;
        $upload_path = $upload_dir . $new_filename;
        
        // Upload file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image = $new_filename;
        } else {
            $_SESSION['error'] = 'Failed to upload image';
            header('Location: /Coursework/index.php?action=create_post');
            exit;
        }
    }
    
    // Create post
    $postModel = new Post();
    $result = $postModel->createPost($title, $content, $user_id, $module_id, $image);
    
    if ($result) {
        $_SESSION['success'] = 'Your question has been posted successfully';
        // Clear form data
        unset($_SESSION['form_data']);
        header('Location: /Coursework/index.php');
    } else {
        $_SESSION['error'] = 'Failed to post your question';
        header('Location: /Coursework/index.php?action=create_post');
    }
    exit;
}

function editPost() {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'You must be logged in to edit a post';
        header('Location: /Coursework/app/views/login.php');
        exit;
    }

    // Get post ID from URL
    $post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$post_id) {
        $_SESSION['error'] = 'Invalid post ID';
        header('Location: /Coursework/index.php');
        exit;
    }

    // Get post details
    $postModel = new Post();
    $post = $postModel->getPostById($post_id);

    // Check if post exists and user is the owner
    if (!$post || $post['user_id'] != $_SESSION['user_id']) {
        $_SESSION['error'] = 'You can only edit your own posts';
        header('Location: /Coursework/index.php');
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate input
        if (empty($_POST['title']) || empty($_POST['content']) || empty($_POST['module_id'])) {
            $_SESSION['error'] = 'Title, content and module are required';
            $_SESSION['form_data'] = $_POST;
            header("Location: /Coursework/index.php?action=edit_post&id=$post_id");
            exit;
        }

        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $module_id = (int)$_POST['module_id'];
        $image = null;

        // Handle image upload if present
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);
            $filesize = $_FILES['image']['size'];

            // Validate file extension
            if (!in_array(strtolower($filetype), $allowed)) {
                $_SESSION['error'] = 'Only JPG, JPEG, PNG, and GIF files are allowed';
                header("Location: /Coursework/index.php?action=edit_post&id=$post_id");
                exit;
            }

            // Validate file size (limit to 2MB)
            $max_size = 2 * 1024 * 1024; // 2MB in bytes
            if ($filesize > $max_size) {
                $_SESSION['error'] = 'Image file size must be less than 2MB';
                header("Location: /Coursework/index.php?action=edit_post&id=$post_id");
                exit;
            }

            // Create upload directory if it doesn't exist
            $upload_dir = '../../public/uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate unique filename
            $new_filename = uniqid() . '.' . $filetype;
            $upload_path = $upload_dir . $new_filename;

            // Upload file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image = $new_filename;
            } else {
                $_SESSION['error'] = 'Failed to upload image';
                header("Location: /Coursework/index.php?action=edit_post&id=$post_id");
                exit;
            }
        }

        // Update post
        $result = $postModel->updatePost($post_id, $title, $content, $module_id, $image);

        if ($result) {
            $_SESSION['success'] = 'Post updated successfully';
            header('Location: /Coursework/index.php');
        } else {
            $_SESSION['error'] = 'Failed to update post';
            header("Location: /Coursework/index.php?action=edit_post&id=$post_id");
        }
        exit;
    }

    // If not POST request, show edit form
    include __DIR__ . '/../views/edit_post.php';
}

function deletePost() {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'You must be logged in to delete a post';
        header('Location: /Coursework/app/views/login.php');
        exit;
    }

    // Get post ID from URL
    $post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$post_id) {
        $_SESSION['error'] = 'Invalid post ID';
        header('Location: /Coursework/index.php');
        exit;
    }

    // Get post details
    $postModel = new Post();
    $post = $postModel->getPostById($post_id);

    // Check if post exists and user is the owner
    if (!$post || $post['user_id'] != $_SESSION['user_id']) {
        $_SESSION['error'] = 'You can only delete your own posts';
        header('Location: /Coursework/index.php');
        exit;
    }

    // Delete post
    $result = $postModel->deletePost($post_id);

    if ($result) {
        $_SESSION['success'] = 'Post deleted successfully';
    } else {
        $_SESSION['error'] = 'Failed to delete post';
    }

    header('Location: /Coursework/index.php');
    exit;
} 