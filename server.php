<?php
require 'vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\GraphQL\QueryType;
use GraphQL\Error\DebugFlag;

header("Access-Control-Allow-Origin: http://localhost"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

// Handle CORS preflight request
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

    // Execute the query with debug flags enabled
    $result = GraphQL::executeQuery($schema, $query);

    // Include the debug flags in the output for more detailed error reporting
    $debugFlags = DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE | DebugFlag::RETHROW_INTERNAL_EXCEPTIONS;

    // Output the result with debug information if an error occurs
    $output = $result->toArray($debugFlags);

} catch (\Exception $e) {
    $output = [
        'error' => [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(), // Include the trace to help with debugging
        ]
    ];
}

header('Content-Type: application/json');
echo json_encode($output);
