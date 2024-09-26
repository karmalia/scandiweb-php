<?php
require 'vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\GraphQL\QueryType;





$allowedOrigins = [
    'http://localhost',
    'https://your-production-domain.com'
];

if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
}
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow these HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow specific headers


// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $schema = new Schema([
        'query' => new QueryType(),
    ]);

    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'] ?? null;

    $result = GraphQL::executeQuery($schema, $query);
    $output = $result->toArray();
} catch (\Exception $e) {
    $output = [
        'error' => [
            'message' => $e->getMessage()
        ]
    ];
}

header('Content-Type: application/json');
echo json_encode($output);
