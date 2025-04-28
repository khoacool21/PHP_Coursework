<?php
/**
 * Template for displaying the users list
 * 
 * @var array $users List of users
 */
?>

<div class="container main-container">
    <div class="content-area">
        <div class="admin-header">
            <h2 class="page-title"><i class="fas fa-users"></i> User Management</h2>
            <div class="admin-actions">
                <a href="/Coursework/index.php?action=create_user" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Create User
                </a>
                
                
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="table-responsive">
            <?php if (empty($users)): ?>
                <div class="alert info"><i class="fas fa-info-circle"></i> No users found. Create your first user!</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= ucfirst($user['role']) ?></td>
                                <td><?= date('d M Y H:i', strtotime($user['created_at'])) ?></td>
                                <td class="action-buttons">
                                    <a href="/Coursework/index.php?action=edit_user&id=<?= $user['id'] ?>" class="btn btn-sm btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="/Coursework/index.php?action=delete_user&id=<?= $user['id'] ?>" 
                                       class="btn btn-sm btn-delete"
                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div> 