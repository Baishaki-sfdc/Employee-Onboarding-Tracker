<?php
if (!isset($_POST['task_id']) || !isset($_POST['status'])) {
    header('Location: index.php');
    exit;
}

try {
    $db = new PDO('mysql:host=localhost;dbname=onboarding_tracker', 'root', 'NewPass123!');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Update task status
    $stmt = $db->prepare("
        UPDATE employee_tasks 
        SET status = ? 
        WHERE id = ?
    ");
    $stmt->execute([$_POST['status'], $_POST['task_id']]);
    
    // Get employee_id for redirect
    $stmt = $db->prepare("SELECT employee_id FROM employee_tasks WHERE id = ?");
    $stmt->execute([$_POST['task_id']]);
    $employee_id = $stmt->fetchColumn();
    
    header("Location: view_tasks.php?employee_id=" . $employee_id);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
