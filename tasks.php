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

    $db->exec("CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        child_id INT NOT NULL,
        task_type ENUM('morning_meds', 'school', 'afternoon_activities', 'evening_meds', 'bedtime') NOT NULL,
        status ENUM('pending', 'completed', 'missed') DEFAULT 'pending',
        notes TEXT,
        due_time TIME,
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
        if (isset($_POST['add_task'])) 
        {
            $stmt = $db->prepare("INSERT INTO tasks (child_id, task_type, status, notes, due_time) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['child_id'],
                $_POST['task_type'],
                $_POST['status'],
                $_POST['notes'],
                $_POST['due_time']
            ]);
            $_SESSION['message'] = "Task added successfully!";
        } 
        elseif (isset($_POST['update_task'])) 
        {
            $stmt = $db->prepare("UPDATE tasks SET child_id=?, task_type=?, status=?, notes=?, due_time=? WHERE id=?");
            $stmt->execute([
                $_POST['child_id'],
                $_POST['task_type'],
                $_POST['status'],
                $_POST['notes'],
                $_POST['due_time'],
                $_POST['task_id']
            ]);
            $_SESSION['message'] = "Task updated successfully!";
        }
        header("Location: tasks.php");
        exit();
    } catch (PDOException $e) 
    {
        $error = "Database error: " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) 
{
    $stmt = $db->prepare("DELETE FROM tasks WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    $_SESSION['message'] = "Task deleted successfully!";
    header("Location: tasks.php");
    exit();
}

$tasks = $db->query("
    SELECT t.*, c.name AS child_name 
    FROM tasks t
    JOIN children c ON t.child_id = c.id
    ORDER BY t.due_time
")->fetchAll();

$children = $db->query("SELECT id, name FROM children ORDER BY name")->fetchAll();

$edit_task = null;
if (isset($_GET['edit'])) 
{
    $stmt = $db->prepare("SELECT * FROM tasks WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit_task = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
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
        .btn-primary {
            background: #2ecc71;
            color: white;
        }
        .btn-primary:hover {
            background: #27ae60;
        }
        .btn-edit {
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
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #eaf2f8;
        }
        .status-pending {
            color: #e67e22;
            font-weight: 500;
        }
        .status-completed {
            color: #27ae60;
            font-weight: 500;
        }
        .status-missed {
            color: #e74c3c;
            font-weight: 500;
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
            .header {
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
            <h1>Daily Task Management</h1>
            <a href="caregiver_dashboard.php" class="btn btn-back">← Back to Dashboard</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <!-- Task Form -->
        <h2><?= $edit_task ? 'Edit' : 'Add' ?> Task</h2>
        <form method="POST">
            <?php if ($edit_task): ?>
                <input type="hidden" name="task_id" value="<?= $edit_task['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="child_id">Child:</label>
                <select id="child_id" name="child_id" required>
                    <?php foreach ($children as $child): ?>
                        <option value="<?= $child['id'] ?>" 
                            <?= ($edit_task && $edit_task['child_id'] == $child['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($child['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="task_type">Task Type:</label>
                <select id="task_type" name="task_type" required>
                    <option value="morning_meds" <?= ($edit_task['task_type'] ?? '') === 'morning_meds' ? 'selected' : '' ?>>Morning Medication</option>
                    <option value="school" <?= ($edit_task['task_type'] ?? '') === 'school' ? 'selected' : '' ?>>School</option>
                    <option value="afternoon_activities" <?= ($edit_task['task_type'] ?? '') === 'afternoon_activities' ? 'selected' : '' ?>>Afternoon Activities</option>
                    <option value="evening_meds" <?= ($edit_task['task_type'] ?? '') === 'evening_meds' ? 'selected' : '' ?>>Evening Medication</option>
                    <option value="bedtime" <?= ($edit_task['task_type'] ?? '') === 'bedtime' ? 'selected' : '' ?>>Bedtime Routine</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="pending" <?= ($edit_task['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= ($edit_task['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="missed" <?= ($edit_task['status'] ?? '') === 'missed' ? 'selected' : '' ?>>Missed</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="due_time">Due Time:</label>
                <input type="time" id="due_time" name="due_time" required 
                       value="<?= $edit_task['due_time'] ?? '' ?>">
            </div>
            
            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes"><?= $edit_task['notes'] ?? '' ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" name="<?= $edit_task ? 'update_task' : 'add_task' ?>">
                <?= $edit_task ? 'Update' : 'Add' ?> Task
            </button>
            
            <?php if ($edit_task): ?>
                <a href="tasks.php" class="btn">Cancel</a>
            <?php endif; ?>
        </form>

        <h2>Current Tasks</h2>
        <?php if (empty($tasks)): ?>
            <p>No tasks found. Add your first task above.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Child</th>
                        <th>Task</th>
                        <th>Due Time</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['child_name']) ?></td>
                            <td>
                                <?= match($task['task_type']) {
                                    'morning_meds' => 'Morning Meds',
                                    'school' => 'School',
                                    'afternoon_activities' => 'Afternoon Activities',
                                    'evening_meds' => 'Evening Meds',
                                    'bedtime' => 'Bedtime Routine',
                                    default => ucfirst($task['task_type'])
                                } ?>
                            </td>
                            <td><?= date('h:i A', strtotime($task['due_time'])) ?></td>
                            <td class="status-<?= $task['status'] ?>">
                                <?= ucfirst($task['status']) ?>
                            </td>
                            <td><?= htmlspecialchars($task['notes']) ?></td>
                            <td>
                                <a href="tasks.php?edit=<?= $task['id'] ?>" class="btn btn-edit">Edit</a>
                                <a href="tasks.php?delete=<?= $task['id'] ?>" class="btn btn-danger" 
                                   onclick="return confirm('Delete this task?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>