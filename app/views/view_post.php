<?php
$title = "View Post";

// Include required models
require_once __DIR__ . '/../models/Comment.php';

// Fetch comments for the post
$commentModel = new Comment();
$comments = $commentModel->getByPostId($post['id']);

// Include the header template
include __DIR__ . '/includes/header.php';

// Include the HTML template
include __DIR__ . '/templates/view_post_html.php';

// Include the footer template
include __DIR__ . '/includes/footer.php';
?> 