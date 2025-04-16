<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') 
{
    header("Location: login.php");
    exit;
}

$donorProfile = [
    "name" => "Unknown",
    "email" => "",
    "phone" => "",
    "address" => ""
];

$totalDonated = "$0";

try 
{
    $stmt = $pdo->prepare("SELECT name, email, phone, address FROM donors WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $donorProfile = $stmt->fetch(PDO::FETCH_ASSOC) ?: $donorProfile;

    $stmt = $pdo->prepare("SELECT SUM(amount) AS total FROM donations WHERE donor_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalDonated = $total['total'] ? '$' . number_format($total['total'], 2) : '$0';

} catch (PDOException $e) 
{
    error_log("Database error: " . $e->getMessage());
    $database_error = "";
}

$page = isset($_GET['page']) ? $_GET['page'] : 'overview';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard | Tender Wings Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root 
        {
            --primary: #4361ee;
            --primary-light: #edf0ff;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --danger: #f72585;
            --success: #4cc9f0;
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
        }
        
        .dashboard-container 
        {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar 
        {
            width: 280px;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            padding: 2rem 0;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            z-index: 100;
            overflow-y: auto;
        }
        
        .sidebar-header 
        {
            padding: 0 1.5rem 1.5rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .sidebar-logo 
        {
            width: 50px;
            height: 50px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--primary);
            font-size: 1.5rem;
        }
        
        .sidebar-title 
        {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .nav-menu 
        {
            flex-grow: 1;
            padding: 0 1rem;
        }
        
        .nav-item 
        {
            margin-bottom: 0.5rem;
        }
        
        .nav-link 
        {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }
        
        .nav-link:hover 
        {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }
        
        .nav-link.active 
        {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 500;
        }
        
        .nav-link i 
        {
            font-size: 1.1rem;
            margin-right: 1rem;
            width: 24px;
            text-align: center;
        }
        
        .sidebar-footer 
        {
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logout-btn 
        {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            font-weight: 500;
        }
        
        .logout-btn:hover 
        {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .logout-btn i 
        {
            margin-right: 0.5rem;
        }
        
        .main-content 
        {
            flex-grow: 1;
            margin-left: 280px;
            padding: 2rem;
            transition: var(--transition);
        }

        .header 
        {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-title 
        {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .user-profile 
        {
            display: flex;
            align-items: center;
        }
        
        .user-avatar 
        {
            width: 40px;
            height: 40px;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 1rem;
        }
        
        .user-info 
        {
            text-align: right;
        }
        
        .user-name 
        {
            font-weight: 500;
        }
        
        .user-role 
        {
            font-size: 0.8rem;
            color: var(--gray);
        }

        .stats-grid 
        {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card 
        {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover 
        {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card::before 
        {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
        }
        
        .stat-title 
        {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .stat-title i 
        {
            margin-right: 0.5rem;
            color: var(--primary);
        }
        
        .stat-value 
        {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .empty-state 
        {
            background: white;
            border-radius: var(--border-radius);
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: var(--box-shadow);
        }
        
        .empty-icon 
        {
            font-size: 3rem;
            color: var(--light-gray);
            margin-bottom: 1rem;
        }
        
        .empty-title 
        {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .empty-text 
        {
            color: var(--gray);
            margin-bottom: 1.5rem;
        }
        
        .btn 
        {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem 1.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .btn:hover 
        {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn i 
        {
            margin-right: 0.5rem;
        }
        
        .alert 
        {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
        }
        
        .alert-danger 
        {
            background: rgba(247, 37, 133, 0.1);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .alert i 
        {
            margin-right: 0.8rem;
        }
        
        .mobile-header 
        {
            display: none;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 90;
        }
        
        .mobile-title 
        {
            font-weight: 600;
        }
        
        .mobile-avatar 
        {
            width: 36px;
            height: 36px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .hamburger 
        {
            position: fixed;
            top: 1rem;
            left: 1rem;
            background: var(--primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 110;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: none;
            display: none;
        }
        
        @media (max-width: 992px) 
        {
            .sidebar 
            {
                transform: translateX(-100%);
            }
            
            .sidebar.active 
            {
                transform: translateX(0);
            }
            
            .main-content 
            {
                margin-left: 0;
                padding-top: 70px;
            }
            
            .mobile-header, .hamburger 
            {
                display: flex;
            }
        }
        
        @keyframes fadeIn 
        {
            from 
            {
                opacity: 0;
                transform: translateY(10px);
            }
            to 
            {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
</head>
<body>
    <div class="mobile-header">
        <div class="mobile-title"><?php echo ucfirst($page); ?></div>
        <div class="mobile-avatar">
            <?php echo strtoupper(substr($donorProfile['name'], 0, 1)); ?>
        </div>
    </div>

    <button class="hamburger" id="hamburger">
        <i class="fas fa-bars"></i>
    </button>

    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <div class="sidebar-title">Donor Dashboard</div>
            </div>
            
            <nav class="nav-menu">
                <div class="nav-item">
                    <a href="donor_dashboard.php?page=overview" class="nav-link <?php echo ($page == 'overview') ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Overview</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="donations.php" class="nav-link">
                        <i class="fas fa-donate"></i>
                        <span>Donation History</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="profile.php" class="nav-link">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <form method="POST" action="logout.php">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>
        <main class="main-content" id="main-content">
            <?php if (isset($database_error)): ?>
                <div class="alert alert-danger fade-in">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $database_error; ?>
                </div>
            <?php endif; ?>
            <div class="header">
                <h1 class="page-title"><?php echo ucfirst($page); ?></h1>
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($donorProfile['name'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?php echo $donorProfile['name']; ?></div>
                        <div class="user-role">Donor</div>
                    </div>
                </div>
            </div>

            <?php if ($page == 'overview'): ?>
                <div class="stats-grid">
                </div>
                
                <div class="empty-state fade-in">
                    <div class="empty-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="empty-title">More Features Available</h3>
                    <p class="empty-text">Visit the other sections using the sidebar menu to see more details about your donations and profile</p>
                    <a href="donations.php" class="btn">
                        <i class="fas fa-donate"></i> View Donation History
                    </a>
                </div>

            <?php else: ?>
                <div class="empty-state fade-in">
                    <div class="empty-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="empty-title">Page Not Found</h3>
                    <p class="empty-text">The requested page does not exist in this demo.</p>
                    <a href="donor_dashboard.php?page=overview" class="btn">
                        <i class="fas fa-arrow-left"></i> Back to Overview
                    </a>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger');
        
        hamburger.addEventListener('click', () => 
        {
            sidebar.classList.toggle('active');
            
            if (sidebar.classList.contains('active')) 
            {
                hamburger.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                hamburger.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });

        document.addEventListener('click', (event) => 
        {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnHamburger = hamburger.contains(event.target);
            
            if (window.innerWidth <= 992 && !isClickInsideSidebar && !isClickOnHamburger && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                hamburger.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });

        function handleResize() 
        {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('active');
                hamburger.innerHTML = '<i class="fas fa-bars"></i>';
            }
        }
        
        window.addEventListener('resize', handleResize);
        handleResize();
        
        document.addEventListener('DOMContentLoaded', () => 
        {
            const currentPage = '<?php echo $page; ?>';
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => 
            {
                if (link.getAttribute('href').includes(currentPage)) 
                {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>