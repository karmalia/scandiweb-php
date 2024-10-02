<?php
require 'vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\GraphQL\QueryType;
use GraphQL\Error\DebugFlag;

header("Access-Control-Allow-Origin: http://localhost"); 
header("Access-Control-Allow-Origin: http://localhost:3000"); 
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
    if ($rawInput === false || empty($rawInput)) {
        throw new \Exception("Invalid or empty request body.");
    }
    
    $input = json_decode($rawInput, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception("Invalid JSON input: " . json_last_error_msg());
    }
    $query = $input['query'] ?? null;
    $variables = $input['variables'] ?? null;
    $operationName = $input['operationName'] ?? null;

    if ($query === null) {
        throw new \Exception("No query found in the request.");
    }

  
   $result = GraphQL::executeQuery($schema, $query, null, null, $variables, $operationName);
   $debugFlags = DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE;
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
