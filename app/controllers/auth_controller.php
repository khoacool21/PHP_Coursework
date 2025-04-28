<?php
session_start();
require_once __DIR__ . '/../models/database_functions.php';

// Get requested action from either POST or GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'register':
        handleRegister();
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        // Redirect to home if no valid action
        header('Location: /Coursework/index.php');
        exit;
}

function handleLogin() {
    // Validate input
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $_SESSION['error'] = 'Please enter both username and password';
        header('Location: /Coursework/app/views/login.php');
        exit;
    }
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Authenticate user
    $userModel = new User();
    $user = $userModel->authenticate($username, $password);
    
    if ($user) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['success'] = 'Welcome back, ' . $user['username'] . '!';
        header('Location: /Coursework/index.php');
    } else {
        // Login failed
        $_SESSION['error'] = 'Invalid username or password';
        header('Location: /Coursework/app/views/login.php');
    }
    exit;
}

function handleRegister() {
    // Validate input
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $_SESSION['error'] = 'All fields are required';
        header('Location: /Coursework/app/views/register.php');
        exit;
    }
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    // Validate password match
    if ($password !== $confirm) {
        $_SESSION['error'] = 'Passwords do not match';
        header('Location: /Coursework/app/views/register.php');
        exit;
    }
    
    // Create user
    $userModel = new User();
    $result = $userModel->create($username, $email, $password);
    
    if ($result) {
        // Registration successful
        $_SESSION['success'] = 'Account created successfully. Please log in.';
        header('Location: /Coursework/app/views/login.php');
    } else {
        // Registration failed
        $_SESSION['error'] = 'Failed to create account. Username or email may already exist.';
        header('Location: /Coursework/app/views/register.php');
    }
    exit;
}

function handleLogout() {
    // Clear all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
    
    // Redirect to home page
    header('Location: /Coursework/index.php');
    exit;
}
?> 