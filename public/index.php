<?php
require '../vendor/autoload.php';


use App\Controller\GraphQLController;

// Handle CORS here...

// Handle the request method...
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Use the controller to handle GraphQL
$controller = new GraphQLController();
$controller->handle();
