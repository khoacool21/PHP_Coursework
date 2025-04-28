<?php
session_start();
require_once __DIR__ . '/../models/database_functions.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load mail configuration
$mail_config = require __DIR__ . '/../config/mail.php';

class AdminController {
    private $pdo;

    public function __construct() {
        $this->pdo = connectDB();
    }

    // Check if user is admin
    public function isAdmin() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        // Get user from database to verify role
        $stmt = $this->pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        return $user && $user['role'] === 'admin';
    }

    // List all users
    public function listUsers() {
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: /Coursework/index.php');
            exit;
        }

        $stmt = $this->pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../views/admin/users.php';
    }

    // Create new user
    public function createUser() {
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: /Coursework/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role = $_POST['role'];

            // Validate input
            if (empty($username) || empty($email) || empty($password)) {
                $_SESSION['error'] = 'All fields are required';
                header('Location: /Coursework/index.php?action=create_user');
                exit;
            }

            // Check if username or email already exists
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Username or email already exists';
                header('Location: /Coursework/index.php?action=create_user');
                exit;
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashedPassword, $role])) {
                $_SESSION['success'] = 'User created successfully';
                header('Location: /Coursework/index.php?action=users');
            } else {
                $_SESSION['error'] = 'Failed to create user';
                header('Location: /Coursework/index.php?action=create_user');
            }
            exit;
        }

        include __DIR__ . '/../views/admin/create_user.php';
    }

    // Edit existing user
    public function editUser() {
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: /Coursework/index.php');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            header('Location: /Coursework/index.php?action=users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $role = $_POST['role'];
            $password = $_POST['password'];

            // Validate input
            if (empty($username) || empty($email)) {
                $_SESSION['error'] = 'Username and email are required';
                header("Location: /Coursework/index.php?action=edit_user&id=$id");
                exit;
            }

            // Check if username or email already exists for other users
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
            $stmt->execute([$username, $email, $id]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Username or email already exists';
                header("Location: /Coursework/index.php?action=edit_user&id=$id");
                exit;
            }

            // Update user
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?");
                $stmt->execute([$username, $email, $hashedPassword, $role, $id]);
            } else {
                $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
                $stmt->execute([$username, $email, $role, $id]);
            }

            $_SESSION['success'] = 'User updated successfully';
            header('Location: /Coursework/index.php?action=users');
            exit;
        }

        // Get user data for editing
        $stmt = $this->pdo->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            header('Location: /Coursework/index.php?action=users');
            exit;
        }

        include __DIR__ . '/../views/admin/edit_user.php';
    }

    // Delete user
    public function deleteUser() {
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: /Coursework/index.php');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            header('Location: /Coursework/index.php?action=users');
            exit;
        }

        // Prevent admin from deleting their own account
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'You cannot delete your own account';
            header('Location: /Coursework/index.php?action=users');
            exit;
        }

        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['success'] = 'User deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete user';
        }

        header('Location: /Coursework/index.php?action=users');
        exit;
    }

    // List all modules
    public function listModules() {
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: /Coursework/index.php');
            exit;
        }

        require_once __DIR__ . '/../models/database_functions.php';
        $moduleModel = new Module();
        $modules = $moduleModel->getAllModules();
        include __DIR__ . '/../views/admin/modules.php';
    }

    // Create new module
    public function createModule() {
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: /Coursework/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);

            // Validate input
            if (empty($name)) {
                $_SESSION['error'] = 'Module name is required';
                header('Location: /Coursework/index.php?action=create_module');
                exit;
            }

            // Check if module name already exists
            require_once __DIR__ . '/../models/database_functions.php';
            $moduleModel = new Module();
            $stmt = $this->pdo->prepare("SELECT id FROM modules WHERE name = ?");
            $stmt->execute([$name]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Module name already exists';
                header('Location: /Coursework/index.php?action=create_module');
                exit;
            }

            // Create module
            if ($moduleModel->createModule($name)) {
                $_SESSION['success'] = 'Module created successfully';
                header('Location: /Coursework/index.php?action=modules');
            } else {
                $_SESSION['error'] = 'Failed to create module';
                header('Location: /Coursework/index.php?action=create_module');
            }
            exit;
        }

        include __DIR__ . '/../views/admin/create_module.php';
    }

    // Edit existing module
    public function editModule() {
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: /Coursework/index.php');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            header('Location: /Coursework/index.php?action=modules');
            exit;
        }

        require_once __DIR__ . '/../models/database_functions.php';
        $moduleModel = new Module();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);

            // Validate input
            if (empty($name)) {
                $_SESSION['error'] = 'Module name is required';
                header("Location: /Coursework/index.php?action=edit_module&id=$id");
                exit;
            }

            // Check if module name already exists for other modules
            $stmt = $this->pdo->prepare("SELECT id FROM modules WHERE name = ? AND id != ?");
            $stmt->execute([$name, $id]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Module name already exists';
                header("Location: /Coursework/index.php?action=edit_module&id=$id");
                exit;
            }

            // Update module
            if ($moduleModel->updateModule($id, $name)) {
                $_SESSION['success'] = 'Module updated successfully';
                header('Location: /Coursework/index.php?action=modules');
            } else {
                $_SESSION['error'] = 'Failed to update module';
                header("Location: /Coursework/index.php?action=edit_module&id=$id");
            }
            exit;
        }

        // Get module data for editing
        $module = $moduleModel->getModuleById($id);
        if (!$module) {
            header('Location: /Coursework/index.php?action=modules');
            exit;
        }

        include __DIR__ . '/../views/admin/edit_module.php';
    }

    // Delete module
    public function deleteModule() {
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: /Coursework/index.php');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            header('Location: /Coursework/index.php?action=modules');
            exit;
        }

        require_once __DIR__ . '/../models/database_functions.php';
        $moduleModel = new Module();

        // Check if module has associated posts
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM posts WHERE module_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            $_SESSION['error'] = 'Cannot delete module with associated posts';
            header('Location: /Coursework/index.php?action=modules');
            exit;
        }

        // Delete module
        if ($moduleModel->deleteModule($id)) {
            $_SESSION['success'] = 'Module deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete module';
        }

        header('Location: /Coursework/index.php?action=modules');
        exit;
    }

    // Handle contact form submissions
    public function sendContact() {
        global $mail_config;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Coursework/index.php?action=contact');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        // Validate input
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: /Coursework/index.php?action=contact');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Please enter a valid email address';
            header('Location: /Coursework/index.php?action=contact');
            exit;
        }
        
        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host = $mail_config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $mail_config['smtp_username'];
            $mail->Password = $mail_config['smtp_password'];
            $mail->SMTPSecure = $mail_config['smtp_encryption'];
            $mail->Port = $mail_config['smtp_port'];
            
            // Recipients
            $mail->setFrom($mail_config['from_email'], $mail_config['from_name']);
            $mail->addAddress($mail_config['from_email']); // Send to admin email
            $mail->addReplyTo($email, $name);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Student\'s Treasure: New Inquiry - ' . $subject;
            
            // Prepare email body with more professional formatting
            $emailBody = "
                <!DOCTYPE html>
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            line-height: 1.6;
                            color: #333333;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                            border: 1px solid #dddddd;
                            border-radius: 5px;
                        }
                        .header {
                            background-color: #f8f9fa;
                            padding: 15px;
                            margin-bottom: 20px;
                            border-bottom: 2px solid #4a90e2;
                        }
                        .header h2 {
                            color: #2c3e50;
                            margin: 0;
                        }
                        .content {
                            padding: 15px 0;
                        }
                        .field {
                            margin-bottom: 15px;
                        }
                        .label {
                            font-weight: bold;
                            color: #4a5568;
                        }
                        .message-box {
                            background-color: #f9f9f9;
                            padding: 15px;
                            border-left: 4px solid #4a90e2;
                            margin-top: 5px;
                        }
                        .footer {
                            margin-top: 30px;
                            font-size: 12px;
                            color: #718096;
                            border-top: 1px solid #edf2f7;
                            padding-top: 15px;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>Student's Treasure - Inquiry</h2>
                        </div>
                        <div class='content'>
                            <p>Dear Administrator,</p>
                            <p>You have received a new inquiry through the Student's Treasure contact form. Please find the details below:</p>
                            
                            <div class='field'>
                                <span class='label'>From:</span>
                                <div>{$name}</div>
                            </div>
                            
                            <div class='field'>
                                <span class='label'>Email Address:</span>
                                <div>{$email}</div>
                            </div>
                            
                            <div class='field'>
                                <span class='label'>Subject:</span>
                                <div>{$subject}</div>
                            </div>
                            
                            <div class='field'>
                                <span class='label'>Message:</span>
                                <div class='message-box'>" . nl2br(htmlspecialchars($message)) . "</div>
                            </div>
                            
                            <p>To respond to this inquiry, you can reply directly to this email.</p>
                        </div>
                        <div class='footer'>
                            
                            <p>&copy; " . date('Y') . " Student's Treasure. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
            ";
            
            $mail->Body = $emailBody;
            $mail->AltBody = "STUDENT'S TREASURE\n\n"
                . "From: {$name}\n"
                . "Email: {$email}\n"
                . "Subject: {$subject}\n\n"
                . "MESSAGE:\n{$message}\n\n"
                . "---\n"
                . "This message was sent via the Student's Treasure contact form.";
            
            // Send email
            $mail->send();
            
            $_SESSION['success'] = 'Your message has been sent successfully!';
            header('Location: /Coursework/index.php');
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Message could not be sent. Error: {$mail->ErrorInfo}";
            header('Location: /Coursework/index.php?action=contact');
            exit;
        }
    }
}

// Get requested action from either POST or GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Create admin controller instance
$adminController = new AdminController();

// Handle different actions
switch ($action) {
    case 'users':
        $adminController->listUsers();
        break;
    case 'create_user':
        $adminController->createUser();
        break;
    case 'edit_user':
        $adminController->editUser();
        break;
    case 'delete_user':
        $adminController->deleteUser();
        break;
    case 'modules':
        $adminController->listModules();
        break;
    case 'create_module':
        $adminController->createModule();
        break;
    case 'edit_module':
        $adminController->editModule();
        break;
    case 'delete_module':
        $adminController->deleteModule();
        break;
    case 'send_contact':
        $adminController->sendContact();
        break;
    default:
        header('Location: /Coursework/index.php');
        exit;
} 