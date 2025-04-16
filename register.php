<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $userCount = $stmt->fetchColumn();

    $role = ($userCount === 0) ? 'admin' : (($userCount < 2) ? 'caregiver' : 'donor');

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $role]);

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Helping Hands</title>
    <style>
        :root 
        {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body 
        {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: radial-gradient(circle at 10% 20%, rgba(67, 97, 238, 0.1) 0%, rgba(248, 249, 250, 1) 90%);
        }
        
        .register-container 
        {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin: 20px;
            transition: transform 0.3s ease;
        }
        
        .register-container:hover 
        {
            transform: translateY(-5px);
        }
        
        .register-header 
        {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .register-header h1 
        {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .register-header p 
        {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .register-body {
            padding: 30px;
        }
        
        .logo 
        {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            display: block;
            border-radius: 50%;
            background: white;
            padding: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .form-group 
        {
            margin-bottom: 20px;
        }
        
        .form-group label 
        {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--dark);
            font-weight: 500;
        }
        
        .form-control 
        {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus 
        {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            outline: none;
        }
        
        .btn 
        {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn:hover 
        {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            transform: translateY(-2px);
        }
        
        .btn:active 
        {
            transform: translateY(0);
        }
        
        .register-footer 
        {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .register-footer a 
        {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .register-footer a:hover 
        {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        .error-message 
        {
            color: var(--danger);
            font-size: 14px;
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background-color: rgba(247, 37, 133, 0.1);
            border-radius: 6px;
        }
        
        .password-strength 
        {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }
        
        .strength-weak 
        {
            color: var(--danger);
        }
        
        .strength-medium 
        {
            color: var(--warning);
        }
        
        .strength-strong {
            color: var(--success);
        }
        
        @media (max-width: 480px) 
        {
            .register-container 
            {
                margin: 10px;
                border-radius: 12px;
            }
            
            .register-header 
            {
                padding: 25px 15px;
            }
            
            .register-body 
            {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <img src="logotw.png" alt="Helping Hands Logo" class="logo">
            <h1>Create Your Account</h1>
            <p>Join our community today</p>
        </div>
        
        <div class="register-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required placeholder="Choose a username">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Create a password">
                    <div id="password-strength" class="password-strength"></div>
                </div>
                
                <button type="submit" class="btn">Sign Up</button>
            </form>
            
            <div class="register-footer">
                <p>Already have an account? <a href="login.php">Log in</a></p>
                <p><a href="index.php">← Return to Homepage</a></p>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!username || !email || !password) {
                alert('Please fill in all fields.');
                e.preventDefault();
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Please enter a valid email address.');
                e.preventDefault();
            } else if (password.length < 8) {
                alert('Password must be at least 8 characters long.');
                e.preventDefault();
            }
        });

        const passwordInput = document.getElementById('password');
        const strengthText = document.getElementById('password-strength');
        
        passwordInput.addEventListener('input', function() 
        {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;

            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            
            if (/\d/.test(password)) strength++;
            
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            if (password.length === 0) 
            {
                strengthText.textContent = '';
                strengthText.className = 'password-strength';
            } else if (strength <= 2) {
                strengthText.textContent = 'Weak password';
                strengthText.className = 'password-strength strength-weak';
            } else if (strength <= 4) {
                strengthText.textContent = 'Medium strength';
                strengthText.className = 'password-strength strength-medium';
            } else {
                strengthText.textContent = 'Strong password';
                strengthText.className = 'password-strength strength-strong';
            }
        });
    </script>
</body>
</html>