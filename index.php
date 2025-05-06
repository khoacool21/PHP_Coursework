<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize posts array
$posts = [];

include 'app/config/database.php';
// Include required models
include 'app/models/database_functions.php';

// Get action from POST or GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Handle different actions
switch ($action) {
    case 'login':
        include 'app/controllers/auth_controller.php';
        exit;
        break;
    
    case 'create_post':
        include 'app/views/create_post.php';
        exit;
        break;
    
    case 'edit_post':
        include 'app/controllers/post_controller.php';
        exit;
        break;
    
    case 'delete_post':
        include 'app/controllers/post_controller.php';
        exit;
        break;
    
    case 'view_post':
        if (isset($_GET['id'])) {
            $post_id = (int)$_GET['id'];
            $postModel = new Post();
            $post = $postModel->getPostById($post_id);
            if ($post) {
                include 'app/views/view_post.php';
                exit;
            }
        }
        header('Location: /Coursework/index.php');
        exit;
        break;
    
    case 'contact':
        include 'app/views/contact.php';
        exit;
        break;
    
    case 'send_contact':
    case 'users':
    case 'create_user':
    case 'edit_user':
    case 'delete_user':
    case 'modules':
    case 'create_module':
    case 'edit_module':
    case 'delete_module':
        include 'app/controllers/admin_controller.php';
        exit;
        break;
    
    case 'module_posts':
        if (isset($_GET['id'])) {
            $module_id = (int)$_GET['id'];
            $postModel = new Post();
            $posts = $postModel->getPostsByModule($module_id);
           
            // Get module name for header
            $moduleModel = new Module();
            $moduleInfo = $moduleModel->getModuleById($module_id);
            if ($moduleInfo) {
                $_SESSION['module_name'] = $moduleInfo['name'];
            }
        }
        break;
    
    case 'search':
        if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
            $searchQuery = trim($_GET['q']);
            $postModel = new Post();
            $posts = $postModel->searchPosts($searchQuery);
            $_SESSION['info'] = count($posts) . ' result(s) found for "' . htmlspecialchars($searchQuery) . '"';
        } else {
            $_SESSION['error'] = 'Please enter a search term.';
            header('Location: index.php');
            exit;
        }
        break;
    
    default:
        // Load all posts on the home page
        $postModel = new Post();
        $posts = $postModel->getAllPosts();
        break;
}

// Get modules for sidebar
$module = new Module();
$moduleList = $module->getAllModules();

// Include header
include __DIR__ . '/app/views/includes/header.php';

// Include main template
include __DIR__ . '/app/views/templates/index.html.php';

// Include footer
include __DIR__ . '/app/views/includes/footer.php';
