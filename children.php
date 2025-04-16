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
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    $db->exec("CREATE DATABASE IF NOT EXISTS `".DB_NAME."`");
    $db->exec("USE `".DB_NAME."`");
    
    $db->exec("CREATE TABLE IF NOT EXISTS `children` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `age` int(11) NOT NULL,
        `status` varchar(50) NOT NULL,
        `last_checkup` date NOT NULL,
        `allergies` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

} catch (PDOException $e) 
{
    die("Database error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    try 
    {
        if (isset($_POST['add_child'])) 
        {
            $stmt = $db->prepare("INSERT INTO children (name, age, status, last_checkup, allergies) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                htmlspecialchars($_POST['name']),
                intval($_POST['age']),
                htmlspecialchars($_POST['status']),
                $_POST['last_checkup'],
                htmlspecialchars($_POST['allergies'])
            ]);
            $_SESSION['message'] = "Child added successfully!";
            
        } elseif (isset($_POST['update_child'])) 
        {
            $stmt = $db->prepare("UPDATE children SET name=?, age=?, status=?, last_checkup=?, allergies=? WHERE id=?");
            $stmt->execute([
                htmlspecialchars($_POST['name']),
                intval($_POST['age']),
                htmlspecialchars($_POST['status']),
                $_POST['last_checkup'],
                htmlspecialchars($_POST['allergies']),
                intval($_POST['child_id'])
            ]);
            $_SESSION['message'] = "Child updated successfully!";
        }
        
        header("Location: children.php");
        exit();
        
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) 
{
    try 
    {
        $stmt = $db->prepare("DELETE FROM children WHERE id=?");
        $stmt->execute([intval($_GET['delete'])]);
        $_SESSION['message'] = "Child deleted successfully!";
        header("Location: children.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error deleting child: " . $e->getMessage();
    }
}

try 
{
    $children = $db->query("SELECT * FROM children ORDER BY name")->fetchAll();
} catch (PDOException $e) 
{
    $error = "Error fetching children: " . $e->getMessage();
    $children = [];
}

$edit_child = null;
if (isset($_GET['edit'])) 
{
    try {
        $stmt = $db->prepare("SELECT * FROM children WHERE id=?");
        $stmt->execute([intval($_GET['edit'])]);
        $edit_child = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Error fetching child data: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Children Management</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background-color: #f5f5f5;
        }
        .container 
        { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header-actions 
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
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin: 0;
        }
        .btn-back 
        {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-back:hover 
        {
            background-color: #2980b9;
        }
        .btn-back::before 
        {
            content: "←";
            margin-right: 5px;
        }
        table 
        { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        th, td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
        }
        th 
        { 
            background-color: #3498db; 
            color: white;
        }
        tr:nth-child(even) 
        {
            background-color: #f2f2f2;
        }
        tr:hover 
        { 
            background-color: #e3f2fd; 
        }
        .form-group 
        { 
            margin-bottom: 15px; 
        }
        label 
        { 
            display: inline-block; 
            width: 150px; 
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, select, textarea 
        { 
            padding: 10px; 
            width: 100%; 
            max-width: 400px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea 
        {
            height: 100px;
        }
        .btn 
        { 
            padding: 10px 15px; 
            cursor: pointer; 
            border: none;
            border-radius: 4px;
            font-weight: bold;
            margin-right: 10px;
            transition: background-color 0.3s;
        }
        .btn-primary 
        { 
            background-color: #2ecc71; 
            color: white; 
        }
        .btn-primary:hover 
        {
            background-color: #27ae60;
        }
        .btn-danger 
        { 
            background-color: #e74c3c; 
            color: white; 
        }
        .btn-danger:hover 
        {
            background-color: #c0392b;
        }
        .btn-edit 
        { 
            background-color: #3498db; 
            color: white; 
        }
        .btn-edit:hover 
        {
            background-color: #2980b9;
        }
        .btn-secondary 
        {
            background-color: #95a5a6;
            color: white;
        }
        .btn-secondary:hover 
        {
            background-color: #7f8c8d;
        }
        .message 
        {
            padding: 10px;
            margin: 10px 0;
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
        .action-btns {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        @media (max-width: 768px) 
        {
            .container {
                padding: 10px;
            }
            label {
                display: block;
                width: 100%;
            }
            .header-actions {
                flex-direction: column;
                align-items: flex-start;
            }
            .action-btns {
                flex-direction: column;
                gap: 5px;
            }
            input, select, textarea {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h1>Children Management</h1>
            <a href="caregiver_dashboard.php" class="btn-back">Back to Dashboard</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>
        
        <h2><?= $edit_child ? 'Edit' : 'Add' ?> Child</h2>
        <form method="POST">
            <?php if ($edit_child): ?>
                <input type="hidden" name="child_id" value="<?= $edit_child['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required 
                       value="<?= $edit_child ? htmlspecialchars($edit_child['name']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" min="0" max="18" required
                       value="<?= $edit_child ? $edit_child['age'] : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="Active" <?= ($edit_child['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= ($edit_child['status'] ?? '') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="Transferred" <?= ($edit_child['status'] ?? '') === 'Transferred' ? 'selected' : '' ?>>Transferred</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="last_checkup">Last Checkup:</label>
                <input type="date" id="last_checkup" name="last_checkup" required
                       value="<?= $edit_child ? $edit_child['last_checkup'] : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="allergies">Allergies:</label>
                <textarea id="allergies" name="allergies"><?= $edit_child ? htmlspecialchars($edit_child['allergies']) : '' ?></textarea>
            </div>
            
            <div>
                <button type="submit" class="btn btn-primary" name="<?= $edit_child ? 'update_child' : 'add_child' ?>">
                    <?= $edit_child ? 'Update' : 'Add' ?> Child
                </button>
                
                <?php if ($edit_child): ?>
                    <a href="children.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>

        <h2>Children List</h2>
        <?php if (empty($children)): ?>
            <p>No children found in the database.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Status</th>
                        <th>Last Checkup</th>
                        <th>Allergies</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($children as $child): ?>
                        <tr>
                            <td><?= htmlspecialchars($child['name']) ?></td>
                            <td><?= $child['age'] ?></td>
                            <td><?= htmlspecialchars($child['status']) ?></td>
                            <td><?= date('M j, Y', strtotime($child['last_checkup'])) ?></td>
                            <td><?= htmlspecialchars($child['allergies']) ?></td>
                            <td class="action-btns">
                                <a href="children.php?edit=<?= $child['id'] ?>" class="btn btn-edit">Edit</a>
                                <a href="children.php?delete=<?= $child['id'] ?>" class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this child?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>