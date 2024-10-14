<?php
require '../vendor/autoload.php';

use App\Controller\GraphQLController;

// Allowed origins
$allowedOrigins = [
    'http://localhost:3000',
    'https://scandiweb-react-fullstack.vercel.app'
];

// Handle CORS headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Credentials: true');
    }
}

// Handle preflight (OPTIONS) request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Use the controller to handle GraphQL
$controller = new GraphQLController();
$controller->handle();
