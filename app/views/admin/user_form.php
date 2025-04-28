<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Coursework/index.php');
    exit;
}

$isEdit = isset($user);
$title = $isEdit ? 'Edit User' : 'Create New User';

include __DIR__ . '/../templates/user_form_html.php';
?> 