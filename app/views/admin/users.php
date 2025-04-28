<?php
include __DIR__ . '/../includes/header.php';

// Check if user is admin
require_once __DIR__ . '/../../controllers/admin_controller.php';


// include __DIR__ . '/../controllers/admin_controller.php';
$adminController = new AdminController();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Coursework/index.php');
    exit;
}

include __DIR__ . '/../templates/users_html.php';

include __DIR__ . '/../includes/footer.php'; ?> 