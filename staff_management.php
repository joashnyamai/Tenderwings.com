<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') 
{
    header("Location: login.php");
    exit;
}

try 
{
    $stmt = $pdo->query("SELECT id, username, email, role FROM users WHERE role IN ('admin', 'caregiver')");
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) 
{
    error_log("Database error: " . $e->getMessage());
    die("An error occurred while fetching staff data.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            font-size: 28px;
            color: var(--dark);
            margin-bottom: 20px;
        }
        .back-btn 
        {
            display: inline-flex;
            align-items: center;
            background-color: var(--primary);
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            transition: var(--transition);
        }
        .back-btn:hover 
        {
            background-color: #3a50c1;
        }
        .back-btn i 
        {
            margin-right: 8px;
        }
        table 
        {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        th 
        {
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #eee;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        td 
        {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        tr:last-child td 
        {
            border-bottom: none;
        }
        tr:hover td 
        {
            background-color: #f9f9f9;
        }
        .user-avatar 
        {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
        }
        .user-cell 
        {
            display: flex;
            align-items: center;
        }
        .user-name 
        {
            font-weight: 500;
        }
        .user-email 
        {
            font-size: 12px;
            color: #777;
        }
        .role-badge 
        {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }
        .role-badge.admin 
        {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--danger);
        }
        .role-badge.caregiver 
        {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }
        .action-buttons 
        {
            display: flex;
            gap: 10px;
        }
        .btn 
        {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .btn i 
        {
            font-size: 14px;
            margin-right: 5px;
        }
        .btn-edit 
        {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        .btn-edit:hover 
        {
            background-color: rgba(40, 167, 69, 0.2);
        }
        .btn-delete 
        {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        .btn-delete:hover 
        {
            background-color: rgba(220, 53, 69, 0.2);
        }
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        <h1>Staff Management</h1>

        <table>
            <thead>
                <tr>
                    <th>Staff Member</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff as $member): ?>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    <?php echo strtoupper(substr($member['username'], 0, 1)); ?>
                                </div>
                                <div>
                                    <div class="user-name"><?php echo htmlspecialchars($member['username']); ?></div>
                                    <div class="user-email"><?php echo htmlspecialchars($member['email']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($member['email']); ?></td>
                        <td>
                            <span class="role-badge <?php echo htmlspecialchars($member['role']); ?>">
                                <?php echo htmlspecialchars(ucfirst($member['role'])); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit_staff.php?id=<?php echo $member['id']; ?>" class="btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_staff.php?id=<?php echo $member['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this staff member?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>