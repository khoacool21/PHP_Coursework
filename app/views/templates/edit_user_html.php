<?php
/**
 * Template for editing a user
 * 
 * @var array $user The user data to edit
 */
?>

<div class="container main-container">
    <div class="content-area">
        <div class="admin-header">
            <h2 class="page-title"><i class="fas fa-user-edit"></i> Edit User</h2>
            <div class="admin-actions">
                <a href="/Coursework/index.php?action=users" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Users
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
            <form action="/Coursework/index.php?action=edit_user&id=<?= htmlspecialchars($user['id']) ?>" 
                  method="post" class="admin-form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required 
                           class="form-control" value="<?= htmlspecialchars($user['username']) ?>"
                           placeholder="Enter username">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required 
                           class="form-control" value="<?= htmlspecialchars($user['email']) ?>"
                           placeholder="Enter email">
                </div>

                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" class="form-control">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 