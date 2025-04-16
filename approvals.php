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

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    try 
    {
        if (isset($_POST['approve'])) 
        {
            $stmt = $pdo->prepare("UPDATE donations SET status = 'approved' WHERE id = ?");
            $stmt->execute([$_POST['donation_id']]);
            $success = "Donation approved successfully!";
        } 
        elseif (isset($_POST['reject'])) 
        {
            $stmt = $pdo->prepare("UPDATE donations SET status = 'rejected' WHERE id = ?");
            $stmt->execute([$_POST['donation_id']]);
            $success = "Donation rejected successfully!";
        }
    } catch (PDOException $e) 
    {
        error_log("Approval error: " . $e->getMessage());
        $error = "Failed to process approval. Please try again.";
    }
}

try 
{
    $stmt = $pdo->query("
        SELECT d.id, u.username, u.email, d.amount, d.donation_type, 
               d.allocation, d.donation_date, d.payment_method, d.status
        FROM donations d
        JOIN users u ON d.user_id = u.id
        WHERE d.status = 'pending'
        ORDER BY d.donation_date DESC
    ");
    $pendingDonations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) 
{
    error_log("Database error: " . $e->getMessage());
    $error = "An error occurred while fetching pending donations.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Approvals</title>
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
        .alert 
        {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .alert-success 
        {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }
        .alert-error 
        {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        .alert i 
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
        .back-button i 
        {
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
        .status-pending {
            color: var(--warning);
            font-weight: 500;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .btn 
        {
            padding: 8px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
        }
        .btn i 
        {
            margin-right: 5px;
        }
        .btn-approve 
        {
            background-color: var(--success);
            color: white;
        }
        .btn-reject 
        {
            background-color: var(--danger);
            color: white;
        }
        .btn:hover 
        {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .no-data 
        {
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
            .action-buttons 
            {
                flex-direction: column;
                gap: 5px;
            }
            table 
            {
                min-width: 600px;
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
            <h1>Donation Approvals</h1>
        </div>
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Pending Approval</h2>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Donor</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pendingDonations)): ?>
                        <tr>
                            <td colspan="7" class="no-data">
                                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                                No pending donations requiring approval
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pendingDonations as $donation): ?>
                            <tr>
                                <td>
                                    <div><?= htmlspecialchars($donation['username']) ?></div>
                                    <div style="font-size: 12px; color: #777;"><?= htmlspecialchars($donation['email']) ?></div>
                                </td>
                                <td class="amount">$<?= number_format($donation['amount'], 2) ?></td>
                                <td><?= htmlspecialchars($donation['donation_type']) ?></td>
                                <td><?= htmlspecialchars($donation['payment_method']) ?></td>
                                <td><?= htmlspecialchars(date('M j, Y', strtotime($donation['donation_date']))) ?></td>
                                <td class="status-pending">Pending</td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="donation_id" value="<?= $donation['id'] ?>">
                                        <div class="action-buttons">
                                            <button type="submit" name="approve" class="btn btn-approve">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button type="submit" name="reject" class="btn btn-reject">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>