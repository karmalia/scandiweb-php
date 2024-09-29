<?php
require 'vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\GraphQL\QueryType;


header("Access-Control-Allow-Origin: http://localhost"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $schema = new Schema([
        'query' => new QueryType(),
        'mutation' => null,
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
