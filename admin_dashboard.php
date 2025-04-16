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
    $stmt = $pdo->query("SELECT id, username, email, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) 
{
    error_log("Database error: " . $e->getMessage());
    die("An error occurred while fetching user data.");
}

$role_counts = [
    'admin' => 0,
    'caregiver' => 0,
    'donor' => 0,
    'total' => count($users)
];
foreach ($users as $user) 
{
    if (isset($role_counts[$user['role']])) 
    {
        $role_counts[$user['role']]++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --secondary: #3f37c9;
            --danger: #f72585;
            --success: #4cc9f0;
            --warning: #f8961e;
            --dark: #212529;
            --light: #f8f9fa;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 80px;
            --header-height: 70px;
            --card-radius: 12px;
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
        .app-container 
        {
            display: flex;
            min-height: 100vh;
        }
        .hamburger 
        {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background-color: var(--dark);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 20px;
            transition: transform 0.3s ease;
        }
        .hamburger.hidden 
        {
            display: none;
        }
        .sidebar 
        {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--dark), #2a2e35);
            color: white;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar.collapsed 
        {
            width: var(--sidebar-collapsed-width);
        }
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .nav-link-text 
        {
            display: none;
        }
        .sidebar.collapsed .nav-link 
        {
            justify-content: center;
        }
        .logo-container 
        {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }
        .logo 
        {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            flex-shrink: 0;
        }
        .logo i 
        {
            font-size: 20px;
        }
        .logo-text 
        {
            font-size: 18px;
            font-weight: 600;
            white-space: nowrap;
            transition: var(--transition);
        }
        .nav-menu 
        {
            flex-grow: 1;
            overflow-y: auto;
        }
        .nav-item 
        {
            margin-bottom: 5px;
            padding: 0 15px;
        }
        .nav-link 
        {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: var(--transition);
        }
        .nav-link:hover 
        {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .nav-link.active 
        {
            background-color: var(--primary);
            color: white;
        }
        .nav-link i 
        {
            font-size: 18px;
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        .nav-link-text 
        {
            white-space: nowrap;
            transition: var(--transition);
        }
        .sidebar-footer 
        {
            padding: 20px 15px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .logout-btn 
        {
            width: 100%;
            background-color: var(--danger);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .logout-btn:hover 
        {
            background-color: #d11a5a;
        }
        .logout-btn i 
        {
            margin-right: 10px;
        }

        .main-content 
        {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            padding: 20px;
        }
        .main-content.shifted 
        {
            margin-left: var(--sidebar-collapsed-width);
        }
        .header 
        {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .header h1 
        {
            font-size: 28px;
            color: var(--dark);
        }
        .user-profile 
        {
            display: flex;
            align-items: center;
        }
        .user-info 
        {
            display: flex;
            flex-direction: column;
        }
        .user-role 
        {
            font-size: 12px;
            color: #777;
        }
        
        .stats-container 
        {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card 
        {
            background-color: white;
            border-radius: var(--card-radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .stat-card::before 
        {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background-color: var(--primary);
        }
        .stat-card.admin::before {
            background-color: var(--danger);
        }
        .stat-card.caregiver::before {
            background-color: var(--success);
        }
        .stat-card.donor::before {
            background-color: var(--warning);
        }
        .stat-title {
            font-size: 14px;
            color: #777;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .stat-title i {
            margin-right: 8px;
            font-size: 16px;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }
        .stat-change {
            font-size: 12px;
            color: #4caf50;
            display: flex;
            align-items: center;
        }
        .stat-change.negative {
            color: #f44336;
        }
        /* Table Styles */
        .table-container {
            background-color: white;
            border-radius: var(--card-radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            overflow-x: auto;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }
        .search-box {
            position: relative;
            width: 250px;
        }
        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
        }
        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
        }
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        th {
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
        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover td {
            background-color: #f9f9f9;
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
        }
        .user-cell {
            display: flex;
            align-items: center;
        }
        .user-name {
            font-weight: 500;
        }
        .user-email {
            font-size: 12px;
            color: #777;
        }
        .role-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }
        .role-badge.admin {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--danger);
        }
        .role-badge.caregiver {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }
        .role-badge.donor {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning);
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn i {
            font-size: 14px;
            margin-right: 5px;
        }
        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
        }
        .btn-edit {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        .btn-edit:hover {
            background-color: rgba(40, 167, 69, 0.2);
        }
        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        .btn-delete:hover {
            background-color: rgba(220, 53, 69, 0.2);
        }
        /* Responsive Styles */
        @media (max-width: 992px) {
            .hamburger {
                display: block;
            }
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.collapsed {
                transform: translateX(0);
                width: var(--sidebar-collapsed-width);
            }
            .main-content {
                margin-left: 0;
            }
            .main-content.shifted {
                margin-left: var(--sidebar-collapsed-width);
            }
        }
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr 1fr;
            }
            .search-box {
                width: 100%;
                margin-top: 10px;
            }
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        @media (max-width: 576px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <button class="hamburger" onclick="toggleSidebar()">&#9776;</button>

    <div class="app-container">
        <div class="sidebar" id="sidebar">
            <div>
                <div class="logo-container">
                    <div class="logo">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="logo-text">Admin Panel</span>
                </div>
                <div class="nav-menu">
                    <!-- Overview -->
                    <div class="nav-item">
                        <a href="admin_dashboard.php" class="nav-link active">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="nav-link-text">Overview</span>
                        </a>
                    </div>

                    <!-- User Management -->
                    <div class="nav-item">
                        <a href="manage_users.php" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span class="nav-link-text">User Management</span>
                        </a>
                    </div>

                    <!-- Staff Management -->
                    <div class="nav-item">
                        <a href="staff_management.php" class="nav-link">
                            <i class="fas fa-user-tie"></i>
                            <span class="nav-link-text">Staff Management</span>
                        </a>
                    </div>

                    <!-- Monthly Donations -->
                    <div class="nav-item">
                        <a href="monthly_donations.php" class="nav-link">
                            <i class="fas fa-hand-holding-usd"></i>
                            <span class="nav-link-text">Monthly Donations</span>
                        </a>
                    </div>

                    <!-- Approvals -->
                    <div class="nav-item">
                        <a href="approvals.php" class="nav-link">
                            <i class="fas fa-check-circle"></i>
                            <span class="nav-link-text">Approvals</span>
                        </a>
                    </div>

                    <!-- Inventory Management -->
                    <div class="nav-item">
                        <a href="inventory_management.php" class="nav-link">
                            <i class="fas fa-boxes"></i>
                            <span class="nav-link-text">Inventory Management</span>
                        </a>
                    </div>
                </div> 
            </div>
            <div class="sidebar-footer">
                <form method="POST" action="logout.php">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-link-text">Logout</span>
                    </button>
                </form>
            </div>
        </div>
        <!-- Main Content -->
        <div class="main-content" id="main-content">
            <div class="header">
                <h1>Dashboard Overview</h1>
                <div class="user-profile">
                    <div class="user-info">
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
            </div>
            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card fade-in" style="animation-delay: 0.1s;">
                    <div class="stat-title">
                        <i class="fas fa-users"></i>
                        Total Users
                    </div>
                    <div class="stat-value"><?php echo $role_counts['total']; ?></div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up"></i> 12% from last month
                    </div>
                </div>
                <div class="stat-card admin fade-in" style="animation-delay: 0.2s;">
                    <div class="stat-title">
                        <i class="fas fa-user-shield"></i>
                        Administrators
                    </div>
                    <div class="stat-value"><?php echo $role_counts['admin']; ?></div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up"></i> 5% from last month
                    </div>
                </div>
                <div class="stat-card caregiver fade-in" style="animation-delay: 0.3s;">
                    <div class="stat-title">
                        <i class="fas fa-hands-helping"></i>
                        Caregivers
                    </div>
                    <div class="stat-value"><?php echo $role_counts['caregiver']; ?></div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up"></i> 18% from last month
                    </div>
                </div>
                <div class="stat-card donor fade-in" style="animation-delay: 0.4s;">
                    <div class="stat-title">
                        <i class="fas fa-heart"></i>
                        Donors
                    </div>
                    <div class="stat-value"><?php echo $role_counts['donor']; ?></div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up"></i> 8% from last month
                    </div>
                </div>
            </div>
            <!-- User Management Table -->
            <div class="table-container fade-in" style="animation-delay: 0.5s;">
                <div class="table-header">
                    <h2 class="table-title">User Management</h2>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search users...">
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="user-name"><?php echo htmlspecialchars($user['username']); ?></div>
                                            <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge <?php echo htmlspecialchars($user['role']); ?>">
                                        <?php echo htmlspecialchars($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const hamburger = document.querySelector('.hamburger');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('shifted');
            hamburger.classList.toggle('hidden');
        }

        document.querySelectorAll('.nav-link').forEach(link => 
        {
            if (link.href === window.location.href) 
            {
                link.classList.add('active');
            }
        });

        document.querySelector('.search-box input').addEventListener('input', function(e) 
        {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => 
            {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) 
                {
                    row.style.display = '';
                } else 
                {
                    row.style.display = 'none';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => 
        {
            const cards = document.querySelectorAll('.fade-in');
            cards.forEach((card, index) => 
            {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>