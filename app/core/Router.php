<?php

class Router
{

    private $routes = [];

    public function add($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function dispatch($requestMethod, $requestUrl)
    {

        foreach ($this->routes as $route) {

            $pattern = preg_replace('/\{id\}/', '([0-9]+)', $route['path']);

            $pattern = "#^" . $pattern . "$#";

            if (
                $route['method'] === $requestMethod &&
                preg_match($pattern, $requestUrl, $matches)
            ) {
                array_shift($matches);

                call_user_func_array($route['callback'], $matches);

                return;
            }
        }

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Route Not Found'
        ]);
    }
}

?>