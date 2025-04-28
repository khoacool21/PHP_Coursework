<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student's Treasure</title>
    <link rel="stylesheet" href="/Coursework/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="nav-container">
                <h1>
                    <a style="color:white" href="/Coursework/index.php">Student's Treasure</a>
                </h1>
                <div class="nav-links">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php 
                        $isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
                                 (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1);
                        if ($isAdmin): 
                        ?>
                            <a href="/Coursework/index.php?action=users" class="nav-admin-btn">
                                <i class="fas fa-users-cog"></i> Manage Users
                            </a>
                            <a href="/Coursework/index.php?action=modules" class="nav-admin-btn">
                                <i class="fas fa-book"></i> Manage Modules
                            </a>
                        <?php endif; ?>
                        <a href="/Coursework/index.php?action=contact" class="nav-contact-btn">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                        <span class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                        <a href="/Coursework/app/controllers/auth_controller.php?action=logout">Logout</a>
                    <?php else: ?>
                        <a href="/Coursework/app/views/login.php">Login</a>
                        <a href="/Coursework/app/views/register.php">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
</body>
</html> 