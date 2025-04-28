<?php
// This file contains only the HTML/presentation part of the view post page
// All logic is in view_post.php
?>

<div class="container main-container">
    <div class="content-area">
        <div class="post-detail">
            <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
            
            <div class="post-meta">
                <p class="post-info"><i class="fas fa-user"></i> Posted by: <?= htmlspecialchars($post['author']) ?></p>
                <p class="post-info"><i class="fas fa-book"></i> Module: <?= htmlspecialchars($post['module']) ?></p>
                <p class="post-info"><i class="fas fa-clock"></i> Posted: <?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></p>
            </div>

            <?php if (!empty($post['image'])): ?>
                <div class="post-image">
                    <img src="/Coursework/public/uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post image">
                </div>
            <?php endif; ?>

            <div class="post-content">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>

            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                <div class="post-actions">
                    <a href="index.php?action=edit_post&id=<?= $post['id'] ?>" class="action-btn action-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="index.php?action=delete_post&id=<?= $post['id'] ?>" 
                       class="action-btn action-delete" 
                       onclick="return confirm('Are you sure you want to delete this post?')">
                        <i class="fas fa-trash-alt"></i> Delete
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Comments Section -->
        <div class="comments-section">
            <h2><i class="fas fa-comments"></i> Comments</h2>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="/Coursework/app/controllers/comment_controller.php" method="post" class="comment-form">
                    <input type="hidden" name="action" value="add_comment">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <div class="form-group">
                        <textarea name="content" class="form-control" rows="3" placeholder="Write your comment here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Comment
                    </button>
                </form>
            <?php else: ?>
                <div class="alert info">
                    <i class="fas fa-info-circle"></i> Please <a href="/Coursework/app/views/login.php">login</a> to comment
                </div>
            <?php endif; ?>

            <div class="comments-list">
                <?php if (empty($comments)): ?>
                    <div class="alert info">
                        <i class="fas fa-info-circle"></i> No comments yet. Be the first to comment!
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-header">
                                <span class="comment-author">
                                    <i class="fas fa-user"></i> <?= htmlspecialchars($comment['author']) ?>
                                </span>
                                <span class="comment-date">
                                    <i class="fas fa-clock"></i> <?= date('F j, Y, g:i a', strtotime($comment['created_at'])) ?>
                                </span>
                            </div>
                            <div class="comment-content">
                                <?= nl2br(htmlspecialchars($comment['content'])) ?>
                            </div>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
                                <form action="/Coursework/app/controllers/comment_controller.php" method="post" class="delete-comment-form">
                                    <input type="hidden" name="action" value="delete_comment">
                                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 