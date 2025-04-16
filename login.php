<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier']);
    $password = $_POST['password'];

    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    }

    $stmt->execute([$identifier]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } elseif ($user['role'] === 'caregiver') {
            header("Location: caregiver_dashboard.php");
        } else {
            header("Location: donor_dashboard.php");
        }
        exit;
    } else {
        echo "<p class='error-message'>Invalid credentials.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Helping Hands</title>
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
        
        .login-container 
        {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin: 20px;
            transition: transform 0.3s ease;
        }
        
        .login-container:hover 
        {
            transform: translateY(-5px);
        }
        
        .login-header 
        {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .login-header h1 
        {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .login-header p 
        {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .login-body 
        {
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
        
        .login-footer 
        {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .login-footer a 
        {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .login-footer a:hover 
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
        
        .divider 
        {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: #999;
            font-size: 14px;
        }
        
        .divider::before, .divider::after 
        {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .divider::before {
            margin-right: 10px;
        }
        
        .divider::after {
            margin-left: 10px;
        }
        
        @media (max-width: 480px) 
        {
            .login-container {
                margin: 10px;
                border-radius: 12px;
            }
            
            .login-header {
                padding: 25px 15px;
            }
            
            .login-body {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="logotw.png" alt="Helping Hands Logo" class="logo">
            <h1>Welcome Back</h1>
            <p>Sign in to continue to your account</p>
        </div>
        
        <div class="login-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="identifier">Email or Username</label>
                    <input type="text" class="form-control" id="identifier" name="identifier" required placeholder="Enter your email or username">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
            
            <div class="login-footer">
                <p>Don't have an account? <a href="register.php">Sign up</a></p>
                <p><a href="index.php">← Return to Homepage</a></p>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) 
        {
            const identifier = document.getElementById('identifier').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!identifier || !password) 
            {
                alert('Please fill in all fields.');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>