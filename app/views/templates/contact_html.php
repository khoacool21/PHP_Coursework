<?php
// This file contains only the HTML/presentation part of the contact form
// All logic is in contact.php
?>

<div class="container main-container">
    <div class="content-area">
        <h2 class="page-title"><i class="fas fa-envelope"></i> Contact Admin</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="/Coursework/index.php?action=send_contact" method="post" class="form-container">
                    <div class="form-group">
                        <label for="name" class="form-label"><i class="fas fa-user"></i> Your Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Your Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label"><i class="fas fa-heading"></i> Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label"><i class="fas fa-align-left"></i> Message</label>
                        <textarea class="form-control" id="message" name="message" rows="8" required></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 