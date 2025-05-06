<?php
// This template expects the following variables to be set:
// $posts - array of posts
// $moduleList - array of modules
?>

<div class="container main-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-section">
            <h3 class="sidebar-title"><i class="fas fa-book-open"></i> Modules</h3>
            <ul class="module-list">
                <?php foreach ($moduleList as $module): ?>
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