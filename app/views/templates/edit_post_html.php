<?php
// This file contains only the HTML/presentation part of the edit post form
// All logic is in edit_post.php
?>

<div class="container main-container">
    <div class="content-area">
        <h2 class="page-title"><i class="fas fa-edit"></i> Edit Post</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="index.php?action=edit_post&id=<?= $post['id'] ?>" method="post" enctype="multipart/form-data" class="post-form">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="module_id">Module</label>
                <select id="module_id" name="module_id" required>
                    <option value="">Select a module</option>
                    <?php foreach ($modules as $module): ?>
                    <option value="<?= $module['id'] ?>" <?= $module['id'] == $post['module_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($module['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Image (optional)</label>
                <?php if (!empty($post['image'])): ?>
                    <div class="current-image">
                        <img src="public/uploads/<?= htmlspecialchars($post['image']) ?>" alt="Current post image">
                        <p>Current image</p>
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/*">
                <small>Max file size: 2MB. Allowed formats: JPG, JPEG, PNG, GIF</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div> 