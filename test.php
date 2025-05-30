<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $db = new PDO('mysql:host=localhost;dbname=onboarding_tracker', 'root', 'NewPass123!');
    echo "Database connection successful!<br>";
    
    $stmt = $db->query("SHOW TABLES");
    echo "Tables in database:<br>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
        echo "<br>";
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
