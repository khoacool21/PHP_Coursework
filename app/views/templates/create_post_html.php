<?php
// This file contains only the HTML/presentation part of the create post form
// All logic is in create_post.php
?>

<div class="container main-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-section">
            <h3 class="sidebar-title"><i class="fas fa-book-open"></i> Modules</h3>
            <ul class="module-list">
                <?php foreach ($modules as $module): ?>
                <li><a class="tag" href="index.php?action=module_posts&id=<?= $module['id'] ?>"><i class="fas fa-folder"></i> <?= htmlspecialchars($module['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Content Area -->
    <div class="content-area">
        <h2><i class="fas fa-question-circle"></i> Post a New Question</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['info'])): ?>
            <div class="alert info"><i class="fas fa-info-circle"></i> <?= $_SESSION['info'] ?></div>
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="/Coursework/app/controllers/post_controller.php" method="post" enctype="multipart/form-data" class="form-container">
                    <input type="hidden" name="action" value="create_post">
                    
                    <div class="form-group">
                        <label for="title" class="form-label"><i class="fas fa-heading"></i> Question Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?= isset($_SESSION['form_data']['title']) ? htmlspecialchars($_SESSION['form_data']['title']) : '' ?>"
                               placeholder="Enter a descriptive title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="module_id" class="form-label"><i class="fas fa-book"></i> Module</label>
                        <select class="form-control" id="module_id" name="module_id" required>
                            <option value="">Select a module</option>
                            <?php foreach ($modules as $module): ?>
                            <option value="<?= $module['id'] ?>" 
                                <?= (isset($_SESSION['form_data']['module_id']) && $_SESSION['form_data']['module_id'] == $module['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($module['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="content" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                        <textarea class="form-control" id="content" name="content" rows="8" 
                                  placeholder="Describe your question in detail" required><?= isset($_SESSION['form_data']['content']) ? htmlspecialchars($_SESSION['form_data']['content']) : '' ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="image" class="form-label"><i class="fas fa-image"></i> Attach Image (Optional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">Supported formats: JPG, JPEG, PNG, GIF (Max size: 2MB)</small>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Post Question</button>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 