<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') 
{
    header("Location: login.php");
    exit;
}

try 
{
    require 'db.php';
    
    $pdo->query("SELECT 1");
} catch (PDOException $e) 
{
    error_log("Database connection error: " . $e->getMessage());
    die("Could not connect to the database. Please try again later.");
}

try 
{
    $stmt = $pdo->query("
        SELECT 
            id,
            name,
            email,
            donation_type,
            allocation,
            amount,
            message,
            payment_method,
            donation_date
        FROM donations
        ORDER BY donation_date DESC
    ");
    
    if (!$stmt) {
        throw new Exception("Failed to execute donation query");
    }
    
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($donations === false) {
        throw new Exception("Failed to fetch donation data");
    }
    
} catch (PDOException $e) 
{
    error_log("Database query error: " . $e->getMessage());
    $error = "An error occurred while fetching donation data. Please try again later.";
} catch (Exception $e) 
{
    error_log("Application error: " . $e->getMessage());
    $error = $e->getMessage();
}

$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$filteredDonations = [];

if (empty($error) && !empty($donations)) 
{
    $filteredDonations = array_filter($donations, function ($donation) use ($currentMonth) 
    {
        return strpos($donation['donation_date'], $currentMonth) === 0;
    });
}

$months = [];
for ($i = 0; $i < 12; $i++) {
    $timestamp = strtotime("-$i months");
    $value = date('Y-m', $timestamp);
    $label = date('F Y', $timestamp);
    $months[$value] = $label;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Donations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root 
        {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --success: #4cc9f0;
            --warning: #f8961e;
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
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        .app-container 
        {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .error-message 
        {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c62828;
            display: flex;
            align-items: center;
        }
        .error-message i 
        {
            margin-right: 10px;
        }
        .back-button 
        {
            display: inline-flex;
            align-items: center;
            background-color: var(--primary);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 20px;
            transition: var(--transition);
        }
        .back-button:hover 
        {
            background-color: var(--primary-light);
        }
        .back-button i {
            margin-right: 8px;
        }

        .header 
        {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header h1 
        {
            font-size: 28px;
            color: var(--dark);
        }
        .filter-container 
        {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .filter-container label {
            font-weight: 500;
        }
        .filter-container select 
        {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
        }
        .table-container 
        {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            overflow-x: auto;
        }
        .table-header 
        {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-title 
        {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }
        table 
        {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
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
        .amount {
            font-weight: 500;
            color: var(--primary);
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #777;
        }
        @media (max-width: 768px) 
        {
            .header 
            {
                flex-direction: column;
                align-items: flex-start;
            }
            .filter-container 
            {
                width: 100%;
            }
            .filter-container select 
            {
                width: 100%;
            }
            table 
            {
                min-width: 600px;
            }
        }
        @media (max-width: 576px) 
        {
            .header h1 {
                font-size: 24px;
            }
            .app-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <a href="admin_dashboard.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
        <div class="header">
            <h1>Monthly Donations Report</h1>
            <div class="filter-container">
                <label for="month-filter">Filter by Month:</label>
                <select id="month-filter" onchange="location.href='?month=' + this.value">
                    <?php foreach ($months as $value => $label): ?>
                        <option value="<?= htmlspecialchars($value) ?>" <?= $value === $currentMonth ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Donation Details for <?= htmlspecialchars(date('F Y', strtotime($currentMonth))) ?></h2>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Allocation</th>
                        <th>Message</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($filteredDonations)): ?>
                        <tr>
                            <td colspan="9" class="no-data">
                                <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                                No donations found for this month
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($filteredDonations as $donation): ?>
                            <tr>
                                <td><?= htmlspecialchars($donation['id']) ?></td>
                                <td><?= htmlspecialchars($donation['name']) ?></td>
                                <td><?= htmlspecialchars($donation['email']) ?></td>
                                <td class="amount">$<?= number_format($donation['amount'], 2) ?></td>
                                <td><?= htmlspecialchars($donation['donation_type']) ?></td>
                                <td><?= htmlspecialchars($donation['allocation']) ?></td>
                                <td><?= !empty($donation['message']) ? htmlspecialchars($donation['message']) : 'N/A' ?></td>
                                <td><?= htmlspecialchars($donation['payment_method']) ?></td>
                                <td><?= htmlspecialchars(date('M j, Y', strtotime($donation['donation_date']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>