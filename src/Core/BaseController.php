<?php

declare(strict_types=1);

//Base controller class for common methods

namespace App\Core;

abstract class BaseController {
    protected function jsonResponse($data, $status = 200){
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}