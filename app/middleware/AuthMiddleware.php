<?php

require_once __DIR__ . '/../helpers/JWT.php';
require_once __DIR__ . '/../helpers/Response.php';

class AuthMiddleware
{

    public static function handle()
    {

        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            Response::json(false, 'Authorization header missing', [], 401);
        }

        $authHeader = $headers['Authorization'];

        //checks token format

        if (!preg_match('/Bearer\s(.*)$/i', $authHeader, $matches)) {
            Response::json(false, 'Invalid Authorization Format', [], 401);
        }

        $token = $matches[1];

        $decoded = JWT::validate($token);

        if (!$decoded) 
        {
            Response::json(false, 'Invalid or Expired Token', [], 401);
        }

        return $decoded;
    }
}

?>