<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'tenderdb');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'caregiver') 
{
    header("Location: login.php");
    exit();
}

try 
{
    $db = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET,
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    $db->exec("CREATE TABLE IF NOT EXISTS health_records (
        id INT AUTO_INCREMENT PRIMARY KEY,
        child_id INT NOT NULL,
        record_date DATE NOT NULL,
        record_type ENUM('checkup', 'vaccination', 'medication', 'allergy', 'condition') NOT NULL,
        height DECIMAL(5,2),
        weight DECIMAL(5,2),
        temperature DECIMAL(4,1),
        notes TEXT,
        details TEXT,
        recorded_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (child_id) REFERENCES children(id)
    )");
    
} catch (PDOException $e) 
{
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    try 
    {
        if (isset($_POST['add_record'])) 
        {
            $stmt = $db->prepare("INSERT INTO health_records 
                (child_id, record_date, record_type, height, weight, temperature, notes, details, recorded_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['child_id'],
                $_POST['record_date'],
                $_POST['record_type'],
                $_POST['height'] ?: null,
                $_POST['weight'] ?: null,
                $_POST['temperature'] ?: null,
                htmlspecialchars($_POST['notes']),
                htmlspecialchars($_POST['details']),
                $_SESSION['user_id']
            ]);
            $_SESSION['message'] = "Health record added successfully!";
        } 
        elseif (isset($_POST['update_record'])) 
        {
            $stmt = $db->prepare("UPDATE health_records SET 
                child_id=?, record_date=?, record_type=?, height=?, weight=?,
                temperature=?, notes=?, details=?
                WHERE id=?");
            $stmt->execute([
                $_POST['child_id'],
                $_POST['record_date'],
                $_POST['record_type'],
                $_POST['height'] ?: null,
                $_POST['weight'] ?: null,
                $_POST['temperature'] ?: null,
                htmlspecialchars($_POST['notes']),
                htmlspecialchars($_POST['details']),
                $_POST['record_id']
            ]);
            $_SESSION['message'] = "Health record updated successfully!";
        }
        header("Location: health.php");
        exit();
    } catch (PDOException $e) 
    {
        $error = "Database error: " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) 
{
    $stmt = $db->prepare("DELETE FROM health_records WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    $_SESSION['message'] = "Health record deleted successfully!";
    header("Location: health.php");
    exit();
}

$records = $db->query("
    SELECT h.*, c.name AS child_name 
    FROM health_records h
    JOIN children c ON h.child_id = c.id
    ORDER BY h.record_date DESC
")->fetchAll();

$children = $db->query("SELECT id, name FROM children ORDER BY name")->fetchAll();

$edit_record = null;
if (isset($_GET['edit'])) 
{
    $stmt = $db->prepare("SELECT * FROM health_records WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit_record = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Child Health Records</title>
    <style>
        body 
        {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f9fc;
        }
        .container 
        {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .header 
        {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        h1 
        {
            color: #2c3e50;
            margin: 0;
        }
        .btn 
        {
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }
        .btn-back 
        {
            background: #3498db;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-back:hover 
        {
            background: #2980b9;
        }
        .btn-primary 
        {
            background: #2ecc71;
            color: white;
        }
        .btn-primary:hover 
        {
            background: #27ae60;
        }
        .btn-edit 
        {
            background: #f39c12;
            color: white;
        }
        .btn-danger 
        {
            background: #e74c3c;
            color: white;
        }
        table 
        {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td 
        {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th 
        {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) 
        {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #eaf2f8;
        }
        .type-checkup 
        {
            color: #3498db;
            font-weight: 500;
        }
        .type-vaccination 
        {
            color: #2ecc71;
            font-weight: 500;
        }
        .type-medication {
            color: #9b59b6;
            font-weight: 500;
        }
        .type-allergy {
            color: #e74c3c;
            font-weight: 500;
        }
        .type-condition {
            color: #f39c12;
            font-weight: 500;
        }
        .form-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        input, select, textarea 
        {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea 
        {
            min-height: 80px;
        }
        .measurement-group 
        {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        .message {
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error 
        {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        @media (max-width: 768px) 
        {
            .header 
            {
                flex-direction: column;
                align-items: flex-start;
            }
            table 
            {
                display: block;
                overflow-x: auto;
            }
            .measurement-group 
            {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Child Health Records</h1>
            <a href="caregiver_dashboard.php" class="btn btn-back">← Back to Dashboard</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <h2>Health History</h2>
        <?php if (empty($records)): ?>
            <p>No health records found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Child</th>
                        <th>Type</th>
                        <th>Measurements</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= date('M j, Y', strtotime($record['record_date'])) ?></td>
                            <td><?= htmlspecialchars($record['child_name']) ?></td>
                            <td class="type-<?= $record['record_type'] ?>">
                                <?= ucfirst($record['record_type']) ?>
                            </td>
                            <td>
                                <?php if ($record['height']): ?>
                                    H: <?= $record['height'] ?> cm<br>
                                <?php endif; ?>
                                <?php if ($record['weight']): ?>
                                    W: <?= $record['weight'] ?> kg<br>
                                <?php endif; ?>
                                <?php if ($record['temperature']): ?>
                                    T: <?= $record['temperature'] ?>°C
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(substr($record['notes'], 0, 30)) ?><?= strlen($record['notes']) > 30 ? '...' : '' ?></td>
                            <td>
                                <a href="health.php?edit=<?= $record['id'] ?>" class="btn btn-edit">View/Edit</a>
                                <a href="health.php?delete=<?= $record['id'] ?>" class="btn btn-danger" 
                                   onclick="return confirm('Delete this health record?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="form-container">
            <h2><?= $edit_record ? 'Edit' : 'Add New' ?> Health Record</h2>
            <form method="POST">
                <?php if ($edit_record): ?>
                    <input type="hidden" name="record_id" value="<?= $edit_record['id'] ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="child_id">Child:</label>
                    <select id="child_id" name="child_id" required>
                        <?php foreach ($children as $child): ?>
                            <option value="<?= $child['id'] ?>" 
                                <?= ($edit_record && $edit_record['child_id'] == $child['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($child['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="record_date">Date:</label>
                    <input type="date" id="record_date" name="record_date" required
                           value="<?= $edit_record['record_date'] ?? date('Y-m-d') ?>">
                </div>
                
                <div class="form-group">
                    <label for="record_type">Record Type:</label>
                    <select id="record_type" name="record_type" required>
                        <option value="checkup" <?= ($edit_record['record_type'] ?? '') === 'checkup' ? 'selected' : '' ?>>Checkup</option>
                        <option value="vaccination" <?= ($edit_record['record_type'] ?? '') === 'vaccination' ? 'selected' : '' ?>>Vaccination</option>
                        <option value="medication" <?= ($edit_record['record_type'] ?? '') === 'medication' ? 'selected' : '' ?>>Medication</option>
                        <option value="allergy" <?= ($edit_record['record_type'] ?? '') === 'allergy' ? 'selected' : '' ?>>Allergy</option>
                        <option value="condition" <?= ($edit_record['record_type'] ?? '') === 'condition' ? 'selected' : '' ?>>Medical Condition</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Measurements:</label>
                    <div class="measurement-group">
                        <div>
                            <label for="height">Height (cm):</label>
                            <input type="number" id="height" name="height" step="0.1" min="0"
                                   value="<?= $edit_record['height'] ?? '' ?>">
                        </div>
                        <div>
                            <label for="weight">Weight (kg):</label>
                            <input type="number" id="weight" name="weight" step="0.1" min="0"
                                   value="<?= $edit_record['weight'] ?? '' ?>">
                        </div>
                        <div>
                            <label for="temperature">Temp (°C):</label>
                            <input type="number" id="temperature" name="temperature" step="0.1" min="0"
                                   value="<?= $edit_record['temperature'] ?? '' ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="details">Details:</label>
                    <textarea id="details" name="details" required><?= $edit_record['details'] ?? '' ?></textarea>
                    <small>For vaccinations: list vaccine name. For medications: dosage and schedule.</small>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea id="notes" name="notes"><?= $edit_record['notes'] ?? '' ?></textarea>
                    <small>Any additional observations or comments</small>
                </div>
                
                <button type="submit" class="btn btn-primary" name="<?= $edit_record ? 'update_record' : 'add_record' ?>">
                    <?= $edit_record ? 'Update' : 'Save' ?> Health Record
                </button>
                
                <?php if ($edit_record): ?>
                    <a href="health.php" class="btn">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>