<?php

namespace App\Controller;

use Dotenv\Dotenv;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\GraphQL\Schema\QueryType;
use App\GraphQL\Schema\MutationType;
use GraphQL\Error\DebugFlag;
use App\Utils\Formatter;



class GraphQLController
{
    public function handle()
    {
        header('Content-Type: application/json');
        try {
            $schema = new Schema([
                'query' => new QueryType(),
                'mutation' => new MutationType(),
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
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();
            $isDev = $_ENV['APP_ENV'] === 'development';

            if ($isDev) {
                $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE);
            } else {
                $output = $result->toArray();
            }
        } catch (\Exception $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            ];
        }


        echo Formatter::toJson($output, true);
    }
}
