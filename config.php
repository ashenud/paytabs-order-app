<?php

require_once __DIR__ . '/vendor/autoload.php';

// Path to the .env file
$envFile = __DIR__ . '/.env';

// Check if the .env file exists
if (!file_exists($envFile)) {
    die("Error: The .env file is missing. Please ensure that the .env file exists in the directory: " . __DIR__);
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$envVars = $dotenv->load();

define('DATABASE_HOST', $envVars['DATABASE_HOST'] ?? '');
define('DATABASE_NAME', $envVars['DATABASE_NAME'] ?? '');
define('DATABASE_USER', $envVars['DATABASE_USER'] ?? '');
define('DATABASE_PASS', $envVars['DATABASE_PASS'] ?? '');

define('PAYTABS_PROFILE_ID', $envVars['PAYTABS_PROFILE_ID'] ?? '');
define('PAYTABS_INTEGRATION_KEY', $envVars['PAYTABS_INTEGRATION_KEY'] ?? '');