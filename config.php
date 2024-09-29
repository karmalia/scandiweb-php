<?php

declare(strict_types=1);
use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access environment variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$db = $_ENV['DB_NAME'] ?? 'scandiwebdb';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';

// Return configuration array
return [
    'database' => [
        'host' => $host,
        'db_name' => $db,
        'username' => $user,
        'password' => $pass,
    ]
];
