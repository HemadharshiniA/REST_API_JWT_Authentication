<?php

class Response
{
    public static function json($success, $msg, $data = [], $code = 200)
    {
        http_response_code($code);

        echo json_encode([
            'success' => $success,
            'message' => $msg,
            'data' => $data
        ]);

        exit;
    }
}

?>