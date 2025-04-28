<?php
/**
 * Template for displaying the modules list
 * 
 * @var array $modules List of modules
 */
?>

<div class="container main-container">
    <div class="content-area">
        <div class="admin-header">
            <h2 class="page-title"><i class="fas fa-book"></i> Module Management</h2>
            <div class="admin-actions">
                <a href="/Coursework/index.php?action=create_module" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Module
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
            <?php if (empty($modules)): ?>
                <div class="alert info"><i class="fas fa-info-circle"></i> No modules found. Create your first module!</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Module Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($modules as $module): ?>
                            <tr>
                                <td><?= $module['id'] ?></td>
                                <td><?= htmlspecialchars($module['name']) ?></td>
                                <td><?= date('d M Y H:i', strtotime($module['created_at'])) ?></td>
                                <td class="action-buttons">
                                    <a href="/Coursework/index.php?action=edit_module&id=<?= $module['id'] ?>" class="btn btn-sm btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="/Coursework/index.php?action=delete_module&id=<?= $module['id'] ?>" 
                                       class="btn btn-sm btn-delete"
                                       onclick="return confirm('Are you sure you want to delete this module? This will only work if no posts are associated with it.')">
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