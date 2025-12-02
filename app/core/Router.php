<?php

class Router {
    protected $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path if needed (for subdirectory deployment)
        // For now assuming root or handling via relative paths if simple
        
        // Simple matching
        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            
            if (is_array($callback)) {
                $controller = new $callback[0]();
                $method = $callback[1];
                return $controller->$method();
            }
            
            if (is_callable($callback)) {
                return call_user_func($callback);
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        echo "404 Not Found";
    }
}
