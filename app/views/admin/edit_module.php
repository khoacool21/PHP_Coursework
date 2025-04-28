<?php
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../../controllers/admin_controller.php';
$adminController = new AdminController();

include __DIR__ . '/../templates/edit_module_html.php';

include __DIR__ . '/../includes/footer.php'; ?> 