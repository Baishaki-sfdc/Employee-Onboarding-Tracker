<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Onboarding Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    $db = new PDO('mysql:host=localhost;dbname=onboarding_tracker', 'root', 'NewPass123!');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    ?>

    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Onboarding Tracker</h1>
            <div class="space-x-4">
                <a href="#" class="hover:text-blue-200" onclick="showSection('employees')">Employees</a>
                <a href="#" class="hover:text-blue-200" onclick="showSection('calendar')">Calendar</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-4">
        <!-- Employee List Section -->
        <div id="employees" class="section">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Employees</h2>
                <button onclick="showModal('addEmployee')" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Add Employee
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php
                $stmt = $db->query("SELECT * FROM employees ORDER BY start_date DESC");
                while ($employee = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='bg-white p-4 rounded-lg shadow'>";
                    echo "<h3 class='font-bold'>{$employee['name']}</h3>";
                    echo "<p class='text-gray-600'>{$employee['email']}</p>";
                    echo "<p class='text-gray-600'>Starts: " . date('M d, Y', strtotime($employee['start_date'])) . "</p>";
                    echo "<div class='mt-2'>";
                    echo "<button onclick='viewTasks({$employee['id']})' class='text-blue-500 hover:text-blue-700'>View Tasks</button>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- Calendar View Section -->
        <div id="calendar" class="section hidden">
            <h2 class="text-xl font-bold mb-6">Upcoming Start Dates</h2>
            <div class="bg-white p-4 rounded-lg shadow">
                <?php
                $stmt = $db->query("SELECT * FROM employees WHERE start_date >= CURDATE() ORDER BY start_date ASC LIMIT 10");
                while ($employee = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='mb-2 p-2 hover:bg-gray-50'>";
                    echo "<span class='font-bold'>" . date('M d, Y', strtotime($employee['start_date'])) . "</span>";
                    echo " - {$employee['name']} ({$employee['department']})";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </main>

    <!-- Add Employee Modal -->
    <div id="addEmployeeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded-lg shadow-lg w-96 mx-auto mt-20">
            <h2 class="text-xl font-bold mb-4">Add New Employee</h2>
            <form action="add_employee.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Department</label>
                    <input type="text" name="department" class="w-full p-2 border rounded">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideModal('addEmployee')" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(sectionId).classList.remove('hidden');
        }

        function showModal(modalId) {
            document.getElementById(modalId + 'Modal').classList.remove('hidden');
        }

        function hideModal(modalId) {
            document.getElementById(modalId + 'Modal').classList.add('hidden');
        }

        function viewTasks(employeeId) {
            // Implement task view functionality
            alert('Viewing tasks for employee ' + employeeId);
        }
    </script>
</body>
</html>
