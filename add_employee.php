<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate form submission
if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['start_date']) || !isset($_POST['department'])) {
    die("Error: All fields are required");
}

try {
    $db = new PDO('mysql:host=localhost;dbname=onboarding_tracker', 'root', 'NewPass123!');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert new employee
    $stmt = $db->prepare("INSERT INTO employees (name, email, start_date, department) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['start_date'],
        $_POST['department']
    ]);
    
    // Get the new employee's ID
    $employeeId = $db->lastInsertId();
    
    // Get all checklist items
    $stmt = $db->query("SELECT id FROM checklist_items");
    $checklistItems = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Create tasks for each checklist item
    $taskStmt = $db->prepare("INSERT INTO employee_tasks (employee_id, checklist_item_id, status) VALUES (?, ?, 'Not Started')");
    foreach ($checklistItems as $itemId) {
        $taskStmt->execute([$employeeId, $itemId]);
    }
    
    header('Location: index.php');
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
