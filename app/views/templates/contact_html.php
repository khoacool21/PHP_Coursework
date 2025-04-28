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

        <form action="/Coursework/index.php?action=send_contact" method="post" class="contact-form">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="10" required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Message</button>
                
            </div>
        </form>
    </div>
</div> 