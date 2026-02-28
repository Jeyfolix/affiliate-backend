<?php
// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Africa/Nairobi');

// JWT Secret (change this!)
define('JWT_SECRET', 'your-super-secret-key-change-this');

// Site URL
define('SITE_URL', 'https://yourusername.github.io/affiliate-website');
?>
