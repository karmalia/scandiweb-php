<?php

declare(strict_types = 1);
// Handle HTTP requests

namespace App\Core;

class Request {
    public function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath(){
        $path = $_SERVER['REQUEST_URI'] ?? '/';
         // Adjust to remove "/pdo-mysql/public/index.php" from the URL
        $cleanPath = str_replace('/pdo-mysql/public/index.php', '', $path);

        // Make sure the returned path starts with a slash (e.g., /users)
        $cleanPath = '/' . ltrim($cleanPath, '/');
       
        return strtok($cleanPath, '?');
    }

    public function getBody(){
        return json_decode(file_get_contents('php://input'), true);
    }
}