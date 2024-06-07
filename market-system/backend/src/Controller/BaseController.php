<?php
namespace App\Controller;

abstract class BaseController {
    protected function successResponse($data, $message = "Operação realizada com sucesso!") {
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    protected function errorResponse($message = "Um erro ocorreu", $statusCode = 400) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }
}
