<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') 
{
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) 
{
    die("Invalid staff ID.");
}
$staff_id = $_GET['id'];

try 
{
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id AND role IN ('admin', 'caregiver')");
    $stmt->execute(['id' => $staff_id]);

    header("Location: staff_management.php");
    exit;
} catch (PDOException $e) 
{
    error_log("Database error: " . $e->getMessage());
    die("An error occurred while deleting the staff member.");
}
?>