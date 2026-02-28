<?php
echo "<h1>Debug Info</h1>";
echo "<pre>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Current Directory: " . __DIR__ . "\n";
echo "PHP Version: " . phpversion() . "\n\n";

echo "Files in current directory:\n";
$files = scandir(__DIR__);
print_r(array_values(array_diff($files, ['.', '..'])));

echo "\n\nServer Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "</pre>";
?>
