<?php
session_start();

// Redirect to login if not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Database connection with error handling
try {
    require 'db.php';
    
    // Test database connection
    $pdo->query("SELECT 1");
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Could not connect to the database. Please try again later.");
}

// Handle form submissions
$error = '';
$success = '';

// Add new item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO inventory 
            (item_name, category, quantity, unit, minimum_stock, location, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['item_name'],
            $_POST['category'],
            $_POST['quantity'],
            $_POST['unit'],
            $_POST['minimum_stock'],
            $_POST['location'],
            $_POST['notes']
        ]);
        $success = "Item added successfully!";
    } catch (PDOException $e) {
        error_log("Inventory add error: " . $e->getMessage());
        $error = "Failed to add item. Please try again.";
    }
}

// Update item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
    try {
        $stmt = $pdo->prepare("
            UPDATE inventory SET 
            item_name = ?, category = ?, quantity = ?, unit = ?, 
            minimum_stock = ?, location = ?, notes = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $_POST['item_name'],
            $_POST['category'],
            $_POST['quantity'],
            $_POST['unit'],
            $_POST['minimum_stock'],
            $_POST['location'],
            $_POST['notes'],
            $_POST['item_id']
        ]);
        $success = "Item updated successfully!";
    } catch (PDOException $e) {
        error_log("Inventory update error: " . $e->getMessage());
        $error = "Failed to update item. Please try again.";
    }
}

// Delete item
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM inventory WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $success = "Item deleted successfully!";
    } catch (PDOException $e) {
        error_log("Inventory delete error: " . $e->getMessage());
        $error = "Failed to delete item. Please try again.";
    }
}

// Fetch all inventory items
try {
    $stmt = $pdo->query("
        SELECT * FROM inventory 
        ORDER BY category, item_name
    ");
    $inventoryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group by category for better organization
    $groupedInventory = [];
    foreach ($inventoryItems as $item) {
        $groupedInventory[$item['category']][] = $item;
    }
} catch (PDOException $e) {
    error_log("Inventory fetch error: " . $e->getMessage());
    $error = "An error occurred while fetching inventory data.";
}

// Fetch low stock items (below minimum stock level)
try {
    $stmt = $pdo->query("
        SELECT * FROM inventory 
        WHERE quantity < minimum_stock
        ORDER BY (quantity/minimum_stock) ASC
    ");
    $lowStockItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Low stock fetch error: " . $e->getMessage());
    // Don't show error for this as it's secondary data
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
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
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        .app-container {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        /* Messages */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }
        .alert-error {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        .alert-warning {
            background-color: #fff8e1;
            color: #ff8f00;
            border-left: 4px solid #ff8f00;
        }
        .alert i {
            margin-right: 10px;
        }
        /* Back Button */
        .back-button {
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
        .back-button:hover {
            background-color: var(--primary-light);
        }
        .back-button i {
            margin-right: 8px;
        }
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header h1 {
            font-size: 28px;
            color: var(--dark);
        }
        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: var(--transition);
        }
        .tab.active {
            border-bottom-color: var(--primary);
            font-weight: 600;
            color: var(--primary);
        }
        .tab:hover:not(.active) {
            background-color: #f5f5f5;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        /* Cards */
        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }
        /* Buttons */
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
        }
        .btn i {
            margin-right: 5px;
        }
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        .btn-success {
            background-color: var(--success);
            color: white;
        }
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        /* Tables */
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid #eee;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover td {
            background-color: #f9f9f9;
        }
        .category-header {
            background-color: #f0f0f0;
            font-weight: 600;
            padding: 10px 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .stock-ok {
            color: var(--success);
            font-weight: 500;
        }
        .stock-low {
            color: var(--warning);
            font-weight: 500;
        }
        .stock-critical {
            color: var(--danger);
            font-weight: 600;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 13px;
        }
        /* Forms */
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
        }
        .form-row {
            display: flex;
            gap: 15px;
        }
        .form-row .form-group {
            flex: 1;
        }
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 20px;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .modal-title {
            font-size: 20px;
            font-weight: 600;
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #777;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Back Button -->
        <a href="admin_dashboard.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>

        <!-- Header -->
        <div class="header">
            <h1>Inventory Management</h1>
            <button id="addItemBtn" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Item
            </button>
        </div>

        <!-- Messages -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Low Stock Warning -->
        <?php if (!empty($lowStockItems)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Warning:</strong> <?= count($lowStockItems) ?> item(s) are below minimum stock levels
            </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" data-tab="inventory">All Inventory</div>
            <div class="tab" data-tab="low-stock">Low Stock</div>
        </div>

        <!-- All Inventory Tab -->
        <div id="inventory" class="tab-content active">
            <?php if (empty($inventoryItems)): ?>
                <div class="card">
                    <div class="no-data">
                        <i class="fas fa-box-open" style="margin-right: 8px;"></i>
                        No inventory items found
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($groupedInventory as $category => $items): ?>
                    <div class="category-header">
                        <i class="fas fa-tag" style="margin-right: 8px;"></i>
                        <?= htmlspecialchars($category) ?>
                    </div>
                    <div class="card">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Min Stock</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <?php 
                                        $stockStatus = '';
                                        $stockRatio = $item['quantity'] / $item['minimum_stock'];
                                        
                                        if ($item['quantity'] <= 0) {
                                            $stockStatus = 'stock-critical';
                                            $statusText = 'Out of Stock';
                                        } elseif ($stockRatio < 0.5) {
                                            $stockStatus = 'stock-critical';
                                            $statusText = 'Critical';
                                        } elseif ($stockRatio < 1) {
                                            $stockStatus = 'stock-low';
                                            $statusText = 'Low';
                                        } else {
                                            $stockStatus = 'stock-ok';
                                            $statusText = 'OK';
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <div><?= htmlspecialchars($item['item_name']) ?></div>
                                                <?php if (!empty($item['notes'])): ?>
                                                    <div style="font-size: 12px; color: #777;"><?= htmlspecialchars($item['notes']) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                                            <td><?= htmlspecialchars($item['unit']) ?></td>
                                            <td><?= htmlspecialchars($item['minimum_stock']) ?></td>
                                            <td><?= htmlspecialchars($item['location']) ?></td>
                                            <td class="<?= $stockStatus ?>"><?= $statusText ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-primary edit-item" 
                                                            data-id="<?= $item['id'] ?>"
                                                            data-name="<?= htmlspecialchars($item['item_name']) ?>"
                                                            data-category="<?= htmlspecialchars($item['category']) ?>"
                                                            data-quantity="<?= htmlspecialchars($item['quantity']) ?>"
                                                            data-unit="<?= htmlspecialchars($item['unit']) ?>"
                                                            data-min="<?= htmlspecialchars($item['minimum_stock']) ?>"
                                                            data-location="<?= htmlspecialchars($item['location']) ?>"
                                                            data-notes="<?= htmlspecialchars($item['notes']) ?>">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <a href="?delete=<?= $item['id'] ?>" class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Are you sure you want to delete this item?')">
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
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Low Stock Tab -->
        <div id="low-stock" class="tab-content">
            <?php if (empty($lowStockItems)): ?>
                <div class="card">
                    <div class="no-data">
                        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                        No items below minimum stock levels
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Min Stock</th>
                                    <th>Unit</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStockItems as $item): ?>
                                    <?php 
                                    $stockStatus = '';
                                    $stockRatio = $item['quantity'] / $item['minimum_stock'];
                                    
                                    if ($item['quantity'] <= 0) {
                                        $stockStatus = 'stock-critical';
                                        $statusText = 'Out of Stock';
                                    } elseif ($stockRatio < 0.5) {
                                        $stockStatus = 'stock-critical';
                                        $statusText = 'Critical';
                                    } else {
                                        $stockStatus = 'stock-low';
                                        $statusText = 'Low';
                                    }
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                                        <td><?= htmlspecialchars($item['category']) ?></td>
                                        <td class="<?= $stockStatus ?>"><?= htmlspecialchars($item['quantity']) ?></td>
                                        <td><?= htmlspecialchars($item['minimum_stock']) ?></td>
                                        <td><?= htmlspecialchars($item['unit']) ?></td>
                                        <td><?= htmlspecialchars($item['location']) ?></td>
                                        <td class="<?= $stockStatus ?>"><?= $statusText ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Add/Edit Item Modal -->
        <div id="itemModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitle">Add New Item</h3>
                    <button class="close-modal">&times;</button>
                </div>
                <form id="itemForm" method="POST">
                    <input type="hidden" name="item_id" id="itemId">
                    <input type="hidden" name="add_item" id="addItemField" value="1">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="item_name">Item Name*</label>
                            <input type="text" id="item_name" name="item_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category*</label>
                            <input type="text" id="category" name="category" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="quantity">Current Quantity*</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="unit">Unit*</label>
                            <input type="text" id="unit" name="unit" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="minimum_stock">Minimum Stock Level*</label>
                            <input type="number" id="minimum_stock" name="minimum_stock" class="form-control" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="location">Storage Location</label>
                            <input type="text" id="location" name="location" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div style="text-align: right; margin-top: 20px;">
                        <button type="button" class="btn btn-danger close-modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and contents
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                tab.classList.add('active');
                document.getElementById(tab.dataset.tab).classList.add('active');
            });
        });

        // Modal functionality
        const modal = document.getElementById('itemModal');
        const addBtn = document.getElementById('addItemBtn');
        const closeBtns = document.querySelectorAll('.close-modal');
        const modalTitle = document.getElementById('modalTitle');
        const itemForm = document.getElementById('itemForm');
        const itemIdField = document.getElementById('itemId');
        const addItemField = document.getElementById('addItemField');

        // Show modal for adding new item
        addBtn.addEventListener('click', () => {
            modalTitle.textContent = 'Add New Item';
            itemIdField.value = '';
            addItemField.value = '1';
            itemForm.reset();
            modal.style.display = 'flex';
        });

        // Show modal for editing item
        document.querySelectorAll('.edit-item').forEach(btn => {
            btn.addEventListener('click', () => {
                modalTitle.textContent = 'Edit Item';
                itemIdField.value = btn.dataset.id;
                addItemField.value = '0';
                
                // Fill form with item data
                document.getElementById('item_name').value = btn.dataset.name;
                document.getElementById('category').value = btn.dataset.category;
                document.getElementById('quantity').value = btn.dataset.quantity;
                document.getElementById('unit').value = btn.dataset.unit;
                document.getElementById('minimum_stock').value = btn.dataset.min;
                document.getElementById('location').value = btn.dataset.location;
                document.getElementById('notes').value = btn.dataset.notes;
                
                modal.style.display = 'flex';
            });
        });

        // Close modal
        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Confirm before deleting
        document.querySelectorAll('.btn-danger[href^="?delete="]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>