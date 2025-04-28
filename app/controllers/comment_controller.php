<?php
session_start();
require_once __DIR__ . '/../models/database_functions.php';
require_once __DIR__ . '/../models/Comment.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Please login to comment';
    header('Location: /Coursework/app/views/login.php');
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add_comment':
        handleAddComment();
        break;
    case 'delete_comment':
        handleDeleteComment();
        break;
    default:
        header('Location: /Coursework/index.php');
        exit;
}

function handleAddComment() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['error'] = 'Invalid request method';
        header('Location: /Coursework/index.php');
        exit;
    }

    $post_id = $_POST['post_id'] ?? 0;
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (empty($content)) {
        $_SESSION['error'] = 'Comment cannot be empty';
        header("Location: /Coursework/index.php?action=view_post&id=$post_id");
        exit;
    }

    $commentModel = new Comment();
    if ($commentModel->create($post_id, $user_id, $content)) {
        $_SESSION['success'] = 'Comment added successfully';
    } else {
        $_SESSION['error'] = 'Failed to add comment';
    }

    header("Location: /Coursework/index.php?action=view_post&id=$post_id");
    exit;
}

function handleDeleteComment() {
    $comment_id = $_POST['comment_id'] ?? 0;
    $post_id = $_POST['post_id'] ?? 0;
    $user_id = $_SESSION['user_id'];

    $commentModel = new Comment();
    if ($commentModel->delete($comment_id, $user_id)) {
        $_SESSION['success'] = 'Comment deleted successfully';
    } else {
        $_SESSION['error'] = 'Failed to delete comment';
    }

    header("Location: /Coursework/index.php?action=view_post&id=$post_id");
    exit;
} 