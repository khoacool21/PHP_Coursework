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

// If no specific action or after handling action, show the main page
include __DIR__ . '/app/views/includes/header.php';
?>


<div class="container main-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-section">
            <h3 class="sidebar-title"><i class="fas fa-book-open"></i> Modules</h3>
            <ul class="module-list">
                <?php
                $module = new Module;
                $moduleList = $module->getAllModules();
                foreach ($moduleList as $module):
                ?>
                <li><a class="tag" href="index.php?action=module_posts&id=<?= $module['id'] ?>"><i class="fas fa-folder"></i> <?= htmlspecialchars($module['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Content Area -->
    <div class="content-area">
        <!-- Toolbar -->
        <div class="toolbar">
            <a href="index.php?action=create_post" class="post-btn">
                <i class="fas fa-plus-circle"></i>Post Question
            </a>
            
            
            <form action="index.php" method="get" class="search-form">
                <div class="search-wrapper">
                    </button>
                    <input type="hidden" name="action" value="search">
                </div>
            </form>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['info'])): ?>
            <div class="alert info"><i class="fas fa-info-circle"></i> <?= $_SESSION['info'] ?></div>
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['module_name'])): ?>
            <h2 class="page-title"><i class="fas fa-folder-open"></i> Module: <?= htmlspecialchars($_SESSION['module_name']) ?></h2>
            <?php unset($_SESSION['module_name']); ?>
        <?php endif; ?>

        <!-- Posts -->
        <div class="post-list">
            <?php if (empty($posts)): ?>
                <div class="alert info"><i class="fas fa-info-circle"></i> No questions found. Be the first to post a question!</div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <h2 class="post-title">
                            <a href="index.php?action=view_post&id=<?= $post['id'] ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h2>
                        <p class="post-preview"><?= htmlspecialchars(substr($post['content'], 0, 150)) ?>...</p>
                        
                        <div class="post-content">
                            <?php if (!empty($post['image'])): ?>
                                <div class="post-image">
                                    <img src="public/uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post image">
                                </div>
                            <?php else: ?>
                                <div class="post-image">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-meta">
                                <p class="post-info"><i class="fas fa-user"></i> Posted by: <?= htmlspecialchars($post['author']) ?></p>
                                <p class="post-info"><i class="fas fa-book"></i> Module: <?= htmlspecialchars($post['module']) ?></p>
                                
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                                    <div class="post-actions">
                                        <a href="index.php?action=edit_post&id=<?= $post['id'] ?>" class="action-btn action-edit"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="index.php?action=delete_post&id=<?= $post['id'] ?>" class="action-btn action-delete" onclick="return confirm('Are you sure you want to delete this post?')"><i class="fas fa-trash-alt"></i> Delete</a>
                                    </div>
                                <?php endif; ?>
                                <a href="index.php?action=view_post&id=<?= $post['id'] ?>" class="action-btn action-reply"><i class="fas fa-reply"></i> Reply</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/app/views/includes/footer.php';?>
