<?php
session_start();

if (!isset($_SESSION['user_id'])) 
{
    header("Location: login.php");
    exit();
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'tenderdb');
define('DB_USER', 'root');
define('DB_PASS', '');

try 
{
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) 
{
    die("Database connection failed: " . $e->getMessage());
}

try 
{
    $stmt = $db->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) 
    {
        die("User not found!");
    }
} catch (PDOException $e) 
{
    die("Database error: " . $e->getMessage());
}

$errorMsg = $successMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $errorMsg = "Invalid email format";
    } else {
        try 
        {
            $db->beginTransaction();
            $checkStmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $checkStmt->execute([$email, $_SESSION['user_id']]);
            
            if ($checkStmt->fetch()) {
                $errorMsg = "Email already in use by another account";
            } else {
                $updateStmt = $db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
                $updateStmt->execute([$username, $email, $_SESSION['user_id']]);
                $successMsg = "Profile updated successfully!";
                $db->commit();

                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();
            }
        } catch (PDOException $e) {
            $db->rollBack();
            $errorMsg = "Update failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - <?= htmlspecialchars($user['username']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root 
        {
            --primary: #4361ee;
            --primary-light: #edf0ff;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --danger: #f72585;
            --danger-light: #fde8e8;
            --success: #4cc9f0;
            --success-light: #e6f7ee;
            --warning: #f8961e;
            --dark: #212529;
            --light: #f8f9fa;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
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
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
            background-image: radial-gradient(circle at 10% 20%, rgba(67, 97, 238, 0.05) 0%, rgba(248, 249, 250, 1) 90%);
        }
        
        .profile-container 
        {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: var(--transition);
        }
        
        .profile-container:hover 
        {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .profile-header 
        {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        
        .profile-avatar 
        {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: white;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--primary);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .profile-name 
        {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .profile-role 
        {
            display: inline-block;
            padding: 0.3rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            font-size: 0.9rem;
            text-transform: capitalize;
        }
        
        .back-btn 
        {
            position: absolute;
            left: 20px;
            top: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .back-btn:hover 
        {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-3px);
        }
        
        .profile-body 
        {
            padding: 2rem;
        }
        
        .section-title 
        {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .section-title::after 
        {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: var(--primary);
        }
        
        .form-group 
        {
            margin-bottom: 1.5rem;
        }
        
        .form-label 
        {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control 
        {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus 
        {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            outline: none;
        }
        
        .btn 
        {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem 1.8rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            gap: 8px;
        }
        
        .btn-primary 
        {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: var(--success-light);
            color: #0a5c36;
            border-left: 4px solid var(--success);
        }
        
        .alert-danger 
        {
            background: var(--danger-light);
            color: #c81e1e;
            border-left: 4px solid var(--danger);
        }
        
        .account-info {
            background: var(--primary-light);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        
        .info-item {
            display: flex;
            margin-bottom: 0.8rem;
        }
        
        .info-label {
            font-weight: 500;
            min-width: 120px;
            color: var(--gray);
        }
        
        .info-value {
            color: var(--dark);
        }
        
        @media (max-width: 768px) 
        {
            .profile-container {
                margin: 1rem;
            }
            
            .profile-header {
                padding: 1.5rem 1rem;
            }
            
            .profile-body {
                padding: 1.5rem;
            }
            
            .info-item {
                flex-direction: column;
            }
            
            .info-label {
                margin-bottom: 0.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <button class="back-btn" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            
            <h1 class="profile-name"><?= htmlspecialchars($user['username']) ?></h1>
            <span class="profile-role"><?= htmlspecialchars($user['role']) ?></span>
        </div>
        
        <div class="profile-body">
            <?php if ($successMsg): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($successMsg) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($errorMsg): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($errorMsg) ?>
                </div>
            <?php endif; ?>
            
            <div class="account-info">
                <h3 class="section-title">Account Information</h3>
                
                <div class="info-item">
                    <span class="info-label">User ID:</span>
                    <span class="info-value"><?= htmlspecialchars($user['id']) ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Account Type:</span>
                    <span class="info-value"><?= htmlspecialchars(ucfirst($user['role'])) ?></span>
                </div>
            </div>
            
            <form method="POST">
                <h3 class="section-title">Edit Profile</h3>
                
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                           value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!username || !email) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return;
            }
            
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return;
            }
            
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            submitBtn.disabled = true;
        });

        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '1';
                alert.style.transform = 'translateY(0)';
            }, 100);

            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    </script>
</body>
</html>