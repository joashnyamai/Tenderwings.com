<?php

session_start();

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

}

try 
{
    $donations = $pdo->query("SELECT name, email, amount, donation_type as type, allocation, DATE_FORMAT(donation_date, '%Y-%m-%d') as date FROM donations ORDER BY donation_date DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) 
{
    $donations = [];
    $_SESSION['errors'][] = "Error fetching donations: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Donation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body 
        {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        .container 
        {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .donate-page 
        {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        h1, h2 
        {
            color: #2c3e50;
            margin-bottom: 1.5rem;
        }
        
        p 
        {
            color: #7f8c8d;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .donation-form 
        {
            margin-top: 2rem;
        }
        
        .form-group 
        {
            margin-bottom: 1.5rem;
        }
        
        .form-group label 
        {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .form-control 
        {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s ease;
        }
        
        .donations-table 
        {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .donations-table th, 
        .donations-table td 
        {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .donations-table th 
        {
            background-color: #3498db;
            color: white;
            font-weight: 600;
        }
        
        .donations-table tr:hover 
        {
            background-color: #f5f5f5;
        }
        
        .btn 
        {
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-back 
        {
            background-color: #95a5a6;
            color: white;
            margin-bottom: 1rem;
        }
        
        .btn-back:hover 
        {
            background-color: #7f8c8d;
        }
        
        .btn-donate 
        {
            background-color: #27ae60;
            color: white;
            width: 100%;
        }
        
        .btn-donate:hover 
        {
            background-color: #2ecc71;
        }
        
        .alert 
        {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
        }
        
        .alert-danger 
        {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success 
        {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .donation-amounts 
        {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .donation-amount 
        {
            flex: 1;
            text-align: center;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .donation-amount:hover 
        {
            border-color: #3498db;
            background-color: #f8f9fa;
        }
        
        .donation-amount.selected 
        {
            border-color: #27ae60;
            background-color: #e8f5e9;
            color: #27ae60;
        }
        
        @media (max-width: 768px) 
        {
            .container 
            {
                padding: 10px;
            }
            
            .donate-page 
            {
                padding: 1.5rem;
            }
            
            .donation-amounts 
            {
                flex-direction: column;
            }
            
            .donations-table 
            {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>   
<div class="container">
        <div class="donate-page">
            <h1>Recent Donations</h1>
            
            <?php if (!empty($donations)): ?>
                <table class="donations-table">
                    <thead>
                        <tr>
                            <th>Donor</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Allocation</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($donation['name']); ?></td>
                                <td><?php echo htmlspecialchars($donation['email']); ?></td>
                                <td>Ksh<?php echo number_format($donation['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($donation['type']); ?></td>
                                <td><?php echo htmlspecialchars($donation['allocation']); ?></td>
                                <td><?php echo htmlspecialchars($donation['date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No donations found yet. Be the first to donate!</p>
            <?php endif; ?>
        </div>
        <a href="donor_dashboard.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a> 
        <div class="donate-page">
            <h1>Make a Donation</h1>
            <p>Your support helps us continue our mission. Thank you for your generosity!</p>
            
            <?php
            if (isset($_SESSION['errors'])) 
            {
                echo '<div class="alert alert-danger">';
                foreach ($_SESSION['errors'] as $error) 
                {
                    echo '<p>' . htmlspecialchars($error) . '</p>';
                }
                echo '</div>';
                unset($_SESSION['errors']);
            }
            
            if (isset($_SESSION['success'])) 
            {
                echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                unset($_SESSION['success']);
            }
            ?>
            
            <form action="donate.php" method="post" class="donation-form">
                <div class="form-group">
                    <label for="name">Full Name*</label>
                    <input type="text" id="name" name="name" class="form-control" 
                           value="<?php echo isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address*</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="donation_type">Donation Type*</label>
                    <select id="donation_type" name="donation_type" class="form-control" required>
                        <option value="">Select Donation Type</option>
                        <option value="One-time" <?php echo (isset($_SESSION['form_data']['donation_type']) && $_SESSION['form_data']['donation_type'] == 'One-time') ? 'selected' : ''; ?>>One-time</option>
                        <option value="Monthly" <?php echo (isset($_SESSION['form_data']['donation_type']) && $_SESSION['form_data']['donation_type'] == 'Monthly') ? 'selected' : ''; ?>>Monthly</option>
                        <option value="Annual" <?php echo (isset($_SESSION['form_data']['donation_type']) && $_SESSION['form_data']['donation_type'] == 'Annual') ? 'selected' : ''; ?>>Annual</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="allocation">Allocation*</label>
                    <select id="allocation" name="allocation" class="form-control" required>
                        <option value="">Select Allocation</option>
                        <option value="General Fund" <?php echo (isset($_SESSION['form_data']['allocation']) && $_SESSION['form_data']['allocation'] == 'General Fund') ? 'selected' : ''; ?>>General Fund</option>
                        <option value="Education" <?php echo (isset($_SESSION['form_data']['allocation']) && $_SESSION['form_data']['allocation'] == 'Education') ? 'selected' : ''; ?>>Education</option>
                        <option value="Healthcare" <?php echo (isset($_SESSION['form_data']['allocation']) && $_SESSION['form_data']['allocation'] == 'Healthcare') ? 'selected' : ''; ?>>Healthcare</option>
                        <option value="Research" <?php echo (isset($_SESSION['form_data']['allocation']) && $_SESSION['form_data']['allocation'] == 'Research') ? 'selected' : ''; ?>>Research</option>
                        <option value="Other" <?php echo (isset($_SESSION['form_data']['allocation']) && $_SESSION['form_data']['allocation'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="amount">Donation Amount (Ksh)*</label>
                    <div class="donation-amounts">
                        <div class="donation-amount" data-amount="250">Ksh250</div>
                        <div class="donation-amount" data-amount="500">Ksh500</div>
                        <div class="donation-amount" data-amount="1000">Ksh1000</div>
                        <div class="donation-amount" data-amount="2500">Ksh2500</div>
                        <div class="donation-amount" data-amount="5000">Ksh5000</div>
                    </div>
                    <input type="number" id="amount" name="amount" min="1" step="0.01" class="form-control" 
                           value="<?php echo isset($_SESSION['form_data']['amount']) ? htmlspecialchars($_SESSION['form_data']['amount']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="payment_method">Payment Method*</label>
                    <select id="payment_method" name="payment_method" class="form-control" required>
                        <option value="">Select Payment Method</option>
                        <option value="credit_card" <?php echo (isset($_SESSION['form_data']['payment_method']) && $_SESSION['form_data']['payment_method'] == 'credit_card') ? 'selected' : ''; ?>>Credit Card</option>
                        <option value="paypal" <?php echo (isset($_SESSION['form_data']['payment_method']) && $_SESSION['form_data']['payment_method'] == 'paypal') ? 'selected' : ''; ?>>PayPal</option>
                        <option value="bank_transfer" <?php echo (isset($_SESSION['form_data']['payment_method']) && $_SESSION['form_data']['payment_method'] == 'bank_transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Message (Optional)</label>
                    <textarea id="message" name="message" class="form-control" rows="4"><?php echo isset($_SESSION['form_data']['message']) ? htmlspecialchars($_SESSION['form_data']['message']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-donate">Donate Now</button>
                </div>
            </form>
            
            <?php unset($_SESSION['form_data']); ?>
        </div>
    </div>

    <script>
        document.querySelectorAll('.donation-amount').forEach(button => 
        {
            button.addEventListener('click', function() 
            {
                document.querySelectorAll('.donation-amount').forEach(btn => 
                {
                    btn.classList.remove('selected');
                });
                
                this.classList.add('selected');
                
                document.getElementById('amount').value = this.getAttribute('data-amount');
            });
        });
    </script>
</body>
</html>