<?php
/**
 * Template for user form (create/edit)
 * 
 * @var array $user The user data to edit (if editing)
 * @var bool $isEdit Whether this is an edit form
 * @var string $title The form title
 */
?>

<div class="admin-interface">
    <div class="admin-header">
        <h2 class="admin-title">
            <i class="fas fa-user-edit"></i> <?= $title ?>
        </h2>
        <a href="index.php?action=users" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <form action="index.php" method="post" class="form-container">
        <input type="hidden" name="action" value="<?= $isEdit ? 'update_user' : 'create_user' ?>">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label class="form-label" for="username">
                <i class="fas fa-user"></i> Username
            </label>
            <input type="text" 
                   class="form-control" 
                   id="username" 
                   name="username" 
                   value="<?= $isEdit ? htmlspecialchars($user['username']) : '' ?>" 
                   required>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">
                <i class="fas fa-envelope"></i> Email
            </label>
            <input type="email" 
                   class="form-control" 
                   id="email" 
                   name="email" 
                   value="<?= $isEdit ? htmlspecialchars($user['email']) : '' ?>" 
                   required>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">
                <i class="fas fa-lock"></i> <?= $isEdit ? 'New Password (leave blank to keep current)' : 'Password' ?>
            </label>
            <input type="password" 
                   class="form-control" 
                   id="password" 
                   name="password" 
                   <?= $isEdit ? '' : 'required' ?>>
        </div>

        <div class="form-group">
            <label class="form-label" for="role">
                <i class="fas fa-user-shield"></i> Role
            </label>
            <select class="form-control" id="role" name="role" required>
                <option value="user" <?= $isEdit && $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $isEdit && $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> <?= $isEdit ? 'Update User' : 'Create User' ?>
        </button>
    </form>
</div> 