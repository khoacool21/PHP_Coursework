<?php
// This file contains only the HTML/presentation part of the login form
// All logic is in login.php
?>

<div class="main-container auth-page">
    <div class="content-area">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3><i class="fas fa-sign-in-alt"></i> Student's Treasure Login</h3>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <form action="/Coursework/index.php" method="post" class="form-container">
                    <input type="hidden" name="action" value="login">
                    
                    <div class="form-group">
                        <label for="username" class="form-label"><i class="fas fa-user"></i> Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </div>
    </div>
</div> 