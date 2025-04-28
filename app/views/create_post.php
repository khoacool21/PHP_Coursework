<?php
$title = "Post a Question";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'You must be logged in to post a question';
    header('Location: login.php');
    exit;
}

// Initialize Module model
require_once __DIR__ . '/../models/database_functions.php';
$moduleModel = new Module();
$modules = $moduleModel->getAllModules();

// Include the HTML template
include __DIR__ . '/includes/header.php';
include __DIR__ . '/templates/create_post_html.php';
include __DIR__ . '/includes/footer.php';
?> 