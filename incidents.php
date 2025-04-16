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

    $db->exec("CREATE TABLE IF NOT EXISTS incidents (
        id INT AUTO_INCREMENT PRIMARY KEY,
        child_id INT NOT NULL,
        incident_type ENUM('injury', 'behavior', 'medical', 'other') NOT NULL,
        incident_date DATE NOT NULL,
        incident_time TIME NOT NULL,
        description TEXT NOT NULL,
        action_taken TEXT NOT NULL,
        reported_by INT NOT NULL,
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
        if (isset($_POST['add_incident'])) 
        {
            $stmt = $db->prepare("INSERT INTO incidents 
                (child_id, incident_type, incident_date, incident_time, description, action_taken, reported_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['child_id'],
                $_POST['incident_type'],
                $_POST['incident_date'],
                $_POST['incident_time'],
                htmlspecialchars($_POST['description']),
                htmlspecialchars($_POST['action_taken']),
                $_SESSION['user_id']
            ]);
            $_SESSION['message'] = "Incident reported successfully!";
        } 
        elseif (isset($_POST['update_incident'])) 
        {
            $stmt = $db->prepare("UPDATE incidents SET 
                child_id=?, incident_type=?, incident_date=?, incident_time=?, 
                description=?, action_taken=? 
                WHERE id=?");
            $stmt->execute([
                $_POST['child_id'],
                $_POST['incident_type'],
                $_POST['incident_date'],
                $_POST['incident_time'],
                htmlspecialchars($_POST['description']),
                htmlspecialchars($_POST['action_taken']),
                $_POST['incident_id']
            ]);
            $_SESSION['message'] = "Incident updated successfully!";
        }
        header("Location: incidents.php");
        exit();
    } catch (PDOException $e) 
    {
        $error = "Database error: " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) 
{
    $stmt = $db->prepare("DELETE FROM incidents WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    $_SESSION['message'] = "Incident record deleted successfully!";
    header("Location: incidents.php");
    exit();
}

$incidents = $db->query("
    SELECT i.*, c.name AS child_name 
    FROM incidents i
    JOIN children c ON i.child_id = c.id
    ORDER BY i.incident_date DESC, i.incident_time DESC
")->fetchAll();

$children = $db->query("SELECT id, name FROM children ORDER BY name")->fetchAll();

$edit_incident = null;
if (isset($_GET['edit'])) 
{
    $stmt = $db->prepare("SELECT * FROM incidents WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit_incident = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports</title>
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
        th, td {
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
        .type-injury {
            color: #e74c3c;
            font-weight: 500;
        }
        .type-behavior {
            color: #9b59b6;
            font-weight: 500;
        }
        .type-medical {
            color: #3498db;
            font-weight: 500;
        }
        .type-other {
            color: #95a5a6;
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
        .message 
        {
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .success 
        {
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Incident Reports</h1>
            <a href="caregiver_dashboard.php" class="btn btn-back">← Back to Dashboard</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <h2>Recent Incidents</h2>
        <?php if (empty($incidents)): ?>
            <p>No incidents reported yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Child</th>
                        <th>Type</th>
                        <th>Time</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($incidents as $incident): ?>
                        <tr>
                            <td><?= date('M j, Y', strtotime($incident['incident_date'])) ?></td>
                            <td><?= htmlspecialchars($incident['child_name']) ?></td>
                            <td class="type-<?= $incident['incident_type'] ?>">
                                <?= ucfirst($incident['incident_type']) ?>
                            </td>
                            <td><?= date('h:i A', strtotime($incident['incident_time'])) ?></td>
                            <td><?= htmlspecialchars(substr($incident['description'], 0, 50)) ?><?= strlen($incident['description']) > 50 ? '...' : '' ?></td>
                            <td>
                                <a href="incidents.php?edit=<?= $incident['id'] ?>" class="btn btn-edit">Edit</a>
                                <a href="incidents.php?delete=<?= $incident['id'] ?>" class="btn btn-danger" 
                                   onclick="return confirm('Delete this incident record?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="form-container">
            <h2><?= $edit_incident ? 'Edit' : 'Report New' ?> Incident</h2>
            <form method="POST">
                <?php if ($edit_incident): ?>
                    <input type="hidden" name="incident_id" value="<?= $edit_incident['id'] ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="child_id">Child:</label>
                    <select id="child_id" name="child_id" required>
                        <?php foreach ($children as $child): ?>
                            <option value="<?= $child['id'] ?>" 
                                <?= ($edit_incident && $edit_incident['child_id'] == $child['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($child['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="incident_type">Incident Type:</label>
                    <select id="incident_type" name="incident_type" required>
                        <option value="injury" <?= ($edit_incident['incident_type'] ?? '') === 'injury' ? 'selected' : '' ?>>Injury</option>
                        <option value="behavior" <?= ($edit_incident['incident_type'] ?? '') === 'behavior' ? 'selected' : '' ?>>Behavioral</option>
                        <option value="medical" <?= ($edit_incident['incident_type'] ?? '') === 'medical' ? 'selected' : '' ?>>Medical</option>
                        <option value="other" <?= ($edit_incident['incident_type'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="incident_date">Date:</label>
                    <input type="date" id="incident_date" name="incident_date" required
                           value="<?= $edit_incident['incident_date'] ?? date('Y-m-d') ?>">
                </div>
                
                <div class="form-group">
                    <label for="incident_time">Time:</label>
                    <input type="time" id="incident_time" name="incident_time" required
                           value="<?= $edit_incident['incident_time'] ?? date('H:i') ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?= $edit_incident['description'] ?? '' ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="action_taken">Action Taken:</label>
                    <textarea id="action_taken" name="action_taken" required><?= $edit_incident['action_taken'] ?? '' ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" name="<?= $edit_incident ? 'update_incident' : 'add_incident' ?>">
                    <?= $edit_incident ? 'Update' : 'Save' ?> Incident Report
                </button>
                
                <?php if ($edit_incident): ?>
                    <a href="incidents.php" class="btn">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>