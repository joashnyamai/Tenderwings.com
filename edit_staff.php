<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') 
{
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) 
{
    die("Invalid staff ID.");
}
$staff_id = $_GET['id'];

try 
{
    $stmt = $pdo->prepare("SELECT id, username, email, role FROM users WHERE id = :id AND role IN ('admin', 'caregiver')");
    $stmt->execute(['id' => $staff_id]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$staff) {
        die("Staff member not found.");
    }
} catch (PDOException $e) 
{
    error_log("Database error: " . $e->getMessage());
    die("An error occurred while fetching staff data.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    if (empty($username) || empty($email) || empty($role)) 
    {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $error = "Invalid email address.";
    } else {
        try 
        {
            $updateStmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
            $updateStmt->execute([
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'id' => $staff_id
            ]);

            header("Location: staff_management.php");
            exit;
        } catch (PDOException $e) 
        {
            error_log("Database error: " . $e->getMessage());
            $error = "An error occurred while updating the staff member.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff Member</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root 
        {
            --primary: #4361ee;
            --success: #4cc9f0;
            --danger: #f72585;
            --dark: #212529;
            --light: #f8f9fa;
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
            background-color: #f5f7fb;
            color: #333;
            line-height: 1.6;
        }
        .container 
        {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 
        {
            font-size: 24px;
            color: var(--dark);
            margin-bottom: 20px;
        }
        form 
        {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        label 
        {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        input, select 
        {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        button 
        {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: var(--transition);
        }
        button:hover 
        {
            background-color: #3a50c1;
        }
        .error 
        {
            color: var(--danger);
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Staff Member</h1>
        <form method="POST">
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($staff['username']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="admin" <?php echo ($staff['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="caregiver" <?php echo ($staff['role'] === 'caregiver') ? 'selected' : ''; ?>>Caregiver</option>
            </select>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>