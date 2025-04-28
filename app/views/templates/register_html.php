<?php
// This file contains only the HTML/presentation part of the registration form
// All logic is in register.php
?>

<div class="main-container auth-page">
    <div class="content-area">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3><i class="fas fa-user-plus"></i> Create Student Account</h3>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <form action="/Coursework/app/controllers/auth_controller.php?action=register" method="post" class="form-container">
                    <input type="hidden" name="action" value="register">
                    
                    <div class="form-group">
                        <label for="username" class="form-label"><i class="fas fa-user"></i> Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Choose a username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Your email address" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label"><i class="fas fa-check-circle"></i> Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Create Account</button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div> 