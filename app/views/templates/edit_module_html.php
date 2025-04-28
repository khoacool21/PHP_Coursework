<?php
/**
 * Template for editing a module
 * 
 * @var array $module The module data to edit
 */
?>

<div class="container main-container">
    <div class="content-area">
        <div class="admin-header">
            <h2 class="page-title"><i class="fas fa-edit"></i> Edit Module</h2>
            <div class="admin-actions">
                <a href="/Coursework/index.php?action=modules" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Modules
                </a>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="form-container">
            <form action="/Coursework/index.php?action=edit_module&id=<?= $module['id'] ?>" method="post" class="admin-form">
                <div class="form-group">
                    <label for="name">Module Name:</label>
                    <input type="text" id="name" name="name" required 
                           class="form-control" placeholder="Enter module name"
                           value="<?= htmlspecialchars($module['name']) ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Module
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 