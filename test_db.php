<?php
// Simple test file - no dependencies
echo "✅ Test page is working!\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "\n";

// Try to include db.php
if (file_exists('api/db.php')) {
    echo "✅ api/db.php exists\n";
    require_once 'api/db.php';
    echo "✅ Database connection attempted\n";
} else {
    echo "❌ api/db.php not found\n";
    echo "Files in current directory:\n";
    print_r(scandir('.'));
}
?>
