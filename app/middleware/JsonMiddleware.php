<?php

require_once __DIR__ . '/../helpers/Response.php';

class JsonMiddleware
{

    public static function handle()
    {

        header('Content-Type: application/json');

        $method = $_SERVER['REQUEST_METHOD'];

        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {

            if (!isset($_SERVER['CONTENT_TYPE']) ||
                strpos($_SERVER['CONTENT_TYPE'], 'application/json') === false) {

                Response::json(false, 'Content-Type must be application/json', [], 400);
            }

            $input = file_get_contents('php://input');

            if (empty($input)) {
                Response::json(false, 'Request body is empty...', [], 400);
            }

            $decoded = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Response::json(false, 'Invalid JSON', [], 400);
            }

            return [
                'body' => $decoded
            ];
        }

        return [];
    }
}
?>