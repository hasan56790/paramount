<?php
// signup.php
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $accountType = $_POST['account_type'];
    $organization = $_POST['organization'] ?? '';
    
    // Validate passwords match
    if ($password !== $confirmPassword) {
        $error = 'Passwords do not match!';
    } else {
        $result = registerUser($firstName, $lastName, $email, $password, $accountType, $organization);
        
        if ($result['success']) {
            $success = $result['message'];
            // Auto-login after successful registration
            $loginResult = loginUser($email, $password);
            if ($loginResult['success']) {
                header('Location: dashboard.php');
                exit;
            }
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Attendance Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Same styles as login.php, adding only additional styles */
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .password-strength {
            height: 5px;
            background: #eee;
            border-radius: 5px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: var(--transition);
        }
        
        .password-strength.weak .password-strength-bar {
            width: 30%;
            background: var(--danger);
        }
        
        .password-strength.medium .password-strength-bar {
            width: 60%;
            background: orange;
        }
        
        .password-strength.strong .password-strength-bar {
            width: 100%;
            background: var(--success);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="welcome-section">
                <h1>Create Your Account</h1>
                <p>Join our advanced attendance management system today. Choose between a personal account for individual use or a shared account for team collaboration.</p>
                <div style="margin-top: 30px;">
                    <h3>Account Types:</h3>
                    <ul style="margin-top: 15px; margin-left: 20px;">
                        <li><strong>Personal ID:</strong> Individual account with full access</li>
                        <li><strong>Shared ID:</strong> Collaborative account for team usage</li>
                    </ul>
                </div>
            </div>
            <div class="login-section">
                <h2>Create New Account</h2>
                
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
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                            <button type="button" class="toggle-password">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength">
                            <div class="password-strength-bar"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                            <button type="button" class="toggle-confirm-password">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group" id="sharedFields" style="display: none;">
                        <label for="organization">Organization Name</label>
                        <input type="text" class="form-control" id="organization" name="organization" placeholder="Enter organization name">
                    </div>
                    
                    <button type="submit" class="btn btn-block">Create Account</button>
                </form>
                
                <div style="text-align: center; margin-top: 20px; color: var(--gray);">
                    <p>Already have an account? <a href="login.php" style="color: var(--primary);">Login Here</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Account type selection
            const loginOptions = document.querySelectorAll('.login-option');
            const sharedFields = document.getElementById('sharedFields');
            
            loginOptions.forEach(option => {
                option.addEventListener('click', function() {
                    loginOptions.forEach(opt => {
                        opt.classList.remove('active');
                        opt.querySelector('input[type="radio"]').checked = false;
                    });
                    this.classList.add('active');
                    this.querySelector('input[type="radio"]').checked = true;
                    
                    // Show/hide organization field
                    if (this.getAttribute('data-type') === 'shared') {
                        sharedFields.style.display = 'block';
                    } else {
                        sharedFields.style.display = 'none';
                    }
                });
            });
            
            // Toggle password visibility
            function setupPasswordToggle(buttonClass, inputId) {
                const toggleButton = document.querySelector(buttonClass);
                const passwordInput = document.getElementById(inputId);
                
                toggleButton.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    const icon = this.querySelector('i');
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }
            
            setupPasswordToggle('.toggle-password', 'password');
            setupPasswordToggle('.toggle-confirm-password', 'confirm_password');
            
            // Password strength indicator
            document.getElementById('password').addEventListener('input', function() {
                const password = this.value;
                const strengthBar = document.getElementById('passwordStrength');
                
                strengthBar.className = 'password-strength';
                
                if (password.length === 0) {
                    return;
                }
                
                let strength = 0;
                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                if (strength < 2) {
                    strengthBar.classList.add('weak');
                } else if (strength < 4) {
                    strengthBar.classList.add('medium');
                } else {
                    strengthBar.classList.add('strong');
                }
            });
        });
    </script>
</body>
</html>