<?php
echo(__DIR__);
require_once __DIR__ . '/vendor/autoload.php'; // Adjust path to autoload.php
// Your application logic follows here
echo "Hello, World!";
use Core\Config; // Make sure to use the correct namespace
$config = new Config(); // This should work if everything is set up correctly

require_once("public/index.php");