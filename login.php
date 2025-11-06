<?php
// login.php
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $accountType = $_POST['account_type'];
    
    $result = loginUser($email, $password);
    
    if ($result['success']) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Attendance Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
        }
        
        .login-container {
            display: flex;
            min-height: 600px;
        }
        
        .welcome-section {
            flex: 1;
            background: linear-gradient(rgba(67, 97, 238, 0.8), rgba(63, 55, 201, 0.9)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .welcome-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .welcome-section p {
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .login-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-section h2 {
            color: var(--primary);
            margin-bottom: 30px;
            text-align: center;
            font-size: 1.8rem;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            outline: none;
        }
        
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
        }
        
        .btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }
        
        .btn-block {
            display: block;
            width: 100%;
        }
        
        .login-options {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .login-option {
            flex: 1;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .login-option:hover {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.05);
        }
        
        .login-option.active {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.1);
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="welcome-section">
                <h1>Advanced Attendance Management System</h1>
                <p>Streamline your attendance tracking with our advanced system. Manage multiple batches, track student attendance with ease, and generate comprehensive reports.</p>
                <div style="margin-top: 30px;">
                    <h3>Features:</h3>
                    <ul style="margin-top: 15px; margin-left: 20px;">
                        <li>Dual login system</li>
                        <li>Batch-wise student management</li>
                        <li>Interactive attendance tracking</li>
                        <li>Date-wise attendance records</li>
                        <li>Advanced search functionality</li>
                    </ul>
                </div>
            </div>
            <div class="login-section">
                <h2>Login to Your Account</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="login-options">
                        <div class="login-option active" data-type="personal">
                            <i class="fas fa-user" style="font-size: 1.5rem; margin-bottom: 10px; color: var(--primary);"></i>
                            <div>Personal ID</div>
                            <input type="radio" name="account_type" value="personal" checked style="display: none;">
                        </div>
                        <div class="login-option" data-type="shared">
                            <i class="fas fa-users" style="font-size: 1.5rem; margin-bottom: 10px; color: var(--primary);"></i>
                            <div>Shared ID</div>
                            <input type="radio" name="account_type" value="shared" style="display: none;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            <button type="button" class="toggle-password">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-block">Login</button>
                </form>
                <div style="text-align: center; margin-top: 20px; color: var(--gray);">
                    <p>Don't have an account? <a href="signup.php" style="color: var(--primary);">Sign Up Now</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Login option selection
            const loginOptions = document.querySelectorAll('.login-option');
            loginOptions.forEach(option => {
                option.addEventListener('click', function() {
                    loginOptions.forEach(opt => {
                        opt.classList.remove('active');
                        opt.querySelector('input[type="radio"]').checked = false;
                    });
                    this.classList.add('active');
                    this.querySelector('input[type="radio"]').checked = true;
                });
            });
            
            // Toggle password visibility
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle eye icon
                const icon = this.querySelector('i');
                if (type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
</body>
</html>