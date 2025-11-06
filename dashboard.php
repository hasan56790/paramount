<?php
// dashboard.php
require_once 'auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$batches = getUserBatches($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Attendance Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Add dashboard styles here */
        /* You can use the same styles from previous implementations */
    </style>
</head>
<body>
    <!-- Dashboard content from previous implementation -->
    <div class="container">
        <div class="dashboard">
            <div class="header">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
                <div class="user-info">
                    <div class="avatar"><?php echo substr($_SESSION['user_name'], 0, 2); ?></div>
                    <div class="user-menu">
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
            
            <!-- Rest of dashboard content -->
        </div>
    </div>
</body>
</html>