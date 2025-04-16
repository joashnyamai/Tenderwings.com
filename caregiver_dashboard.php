<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'caregiver')) {
    header("Location: login.php");
    exit;
}

$child_count = 0;
$pending_tasks = 0;
$incident_count = 0;

try 
{
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total_children FROM children");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $child_count = $result ? $result['total_children'] : 0;

    $stmt = $pdo->prepare("SELECT COUNT(*) AS total_tasks FROM tasks WHERE status = 'pending'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pending_tasks = $result ? $result['total_tasks'] : 0;

    $stmt = $pdo->prepare("SELECT COUNT(*) AS total_incidents FROM incidents");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $incident_count = $result ? $result['total_incidents'] : 0;

} catch (PDOException $e) 
{
    error_log("Database error: " . $e->getMessage());
    $database_error = "Some data may not be available at this time";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caregiver Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root 
        {
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
        .sidebar 
        {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--dark), #2a2e35);
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            z-index: 100;
            overflow-y: auto;
        }
        .nav-menu 
        {
            flex-grow: 1;
        }
        .nav-item 
        {
            margin-bottom: 5px;
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
        
        .main-content 
        {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: var(--transition);
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
        .stat-card:hover 
        {
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
        .stat-title 
        {
            font-size: 14px;
            color: #777;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .stat-title i 
        {
            margin-right: 8px;
        }
        .stat-value 
        {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }
        .error-message 
        {
            color: var(--danger);
            background-color: #fdecea;
            padding: 10px 15px;
            border-radius: var(--card-radius);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .error-message i 
        {
            margin-right: 10px;
        }
        @keyframes fadeIn 
        {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in 
        {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span class="logo-text">Caregiver Panel</span>
            </div>
            <div class="nav-menu">

                <div class="nav-item">
                    <a href="caregiver_dashboard.php" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Overview</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="children.php" class="nav-link">
                        <i class="fas fa-user"></i>
                        <span>Child Profile</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="tasks.php" class="nav-link">
                        <i class="fas fa-tasks"></i>
                        <span>Task Checklist</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="incidents.php" class="nav-link">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Incident Reporting</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="health.php" class="nav-link">
                        <i class="fas fa-heartbeat"></i>
                        <span>Health Status</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-footer">
            <form method="POST" action="logout.php">
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

 
    <div class="main-content">
        <div class="header">
            <h1>Dashboard Overview</h1>
        </div>
        
        <?php if (isset($database_error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $database_error; ?>
            </div>
        <?php endif; ?>
        
        
        <div class="stats-container">
            <div class="stat-card fade-in" style="animation-delay: 0.1s;">
                <div class="stat-title">
                    <i class="fas fa-child"></i>
                    Total Children
                </div>
                <div class="stat-value"><?php echo $child_count; ?></div>
                <a href="profile.php" style="color: var(--primary); text-decoration: none; font-size: 14px;">
                    View all children <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="stat-card fade-in" style="animation-delay: 0.2s;">
                <div class="stat-title">
                    <i class="fas fa-tasks"></i>
                    Pending Tasks
                </div>
                <div class="stat-value"><?php echo $pending_tasks; ?></div>
                <a href="tasks.php" style="color: var(--primary); text-decoration: none; font-size: 14px;">
                    Manage tasks <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="stat-card fade-in" style="animation-delay: 0.3s;">
                <div class="stat-title">
                    <i class="fas fa-exclamation-circle"></i>
                    Reported Incidents
                </div>
                <div class="stat-value"><?php echo $incident_count; ?></div>
                <a href="incidents.php" style="color: var(--primary); text-decoration: none; font-size: 14px;">
                    View incidents <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</body>
</html>