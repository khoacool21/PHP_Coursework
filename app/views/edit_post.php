<?php
$title = "Edit Post";

// Initialize Module model for the form
require_once __DIR__ . '/../models/database_functions.php';
$moduleModel = new Module();
$modules = $moduleModel->getAllModules();

// Include header
include __DIR__ . '/includes/header.php';

// Include the HTML template
include __DIR__ . '/templates/edit_post_html.php';

// Include footer
include __DIR__ . '/includes/footer.php';
?> 