<?php
if (!isset($_GET['employee_id'])) {
    header('Location: index.php');
    exit;
}

try {
    $db = new PDO('mysql:host=localhost;dbname=onboarding_tracker', 'root', 'NewPass123!');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get employee details
    $stmt = $db->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$_GET['employee_id']]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get tasks with checklist items
    $stmt = $db->prepare("
        SELECT et.*, ci.category, ci.item_name, ci.description 
        FROM employee_tasks et 
        JOIN checklist_items ci ON et.checklist_item_id = ci.id 
        WHERE et.employee_id = ?
        ORDER BY ci.category, ci.item_name
    ");
    $stmt->execute([$_GET['employee_id']]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks - <?php echo htmlspecialchars($employee['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto">
            <a href="index.php" class="text-white hover:text-blue-200">← Back to Dashboard</a>
        </div>
    </nav>

    <main class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($employee['name']); ?>'s Onboarding Tasks</h1>
            <p class="text-gray-600 mb-6">
                Start Date: <?php echo date('M d, Y', strtotime($employee['start_date'])); ?> |
                Department: <?php echo htmlspecialchars($employee['department']); ?>
            </p>

            <?php
            $categories = ['HR', 'IT', 'Manager'];
            foreach ($categories as $category) {
                echo "<div class='mb-8'>";
                echo "<h2 class='text-xl font-semibold mb-4'>$category Tasks</h2>";
                echo "<div class='space-y-4'>";
                
                foreach ($tasks as $task) {
                    if ($task['category'] === $category) {
                        $statusColors = [
                            'Not Started' => 'bg-gray-100',
                            'In Progress' => 'bg-yellow-100',
                            'Done' => 'bg-green-100'
                        ];
                        $bgColor = $statusColors[$task['status']];
                        
                        echo "<div class='$bgColor p-4 rounded-lg'>";
                        echo "<div class='flex justify-between items-center'>";
                        echo "<div>";
                        echo "<h3 class='font-medium'>" . htmlspecialchars($task['item_name']) . "</h3>";
                        echo "<p class='text-gray-600'>" . htmlspecialchars($task['description']) . "</p>";
                        echo "</div>";
                        echo "<div class='ml-4'>";
                        echo "<form action='update_task.php' method='POST' class='inline'>";
                        echo "<input type='hidden' name='task_id' value='{$task['id']}'>";
                        echo "<select name='status' onchange='this.form.submit()' class='p-2 rounded border'>";
                        foreach (['Not Started', 'In Progress', 'Done'] as $status) {
                            $selected = $status === $task['status'] ? 'selected' : '';
                            echo "<option value='$status' $selected>$status</option>";
                        }
                        echo "</select>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
                
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </main>
</body>
</html>
