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

        // Check for exact match first
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

        // Check for pattern matches (routes with parameters)
        // Look for routes that contain regex patterns
        foreach ($this->routes[$method] as $route => $callback) {
            // Check if route contains parameter patterns like (\d+)
            if (preg_match('/\(.*\)/', $route)) {
                // Create a proper regex by escaping the static parts and keeping the dynamic parts
                $regex = $this->createRegexFromRoute($route);

                if (preg_match($regex, $path, $matches)) {
                    // Remove the full match (index 0)
                    array_shift($matches);

                    if (is_array($callback)) {
                        $controller = new $callback[0]();
                        $method = $callback[1];
                        return $controller->$method(...$matches);
                    }

                    if (is_callable($callback)) {
                        return call_user_func_array($callback, $matches);
                    }
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        ErrorHandler::showErrorPage(404, 'Page Not Found', 'The page you are looking for could not be found.');
    }

    /**
     * Create a regex from a route pattern by escaping static parts
     *
     * @param string $route
     * @return string
     */
    private function createRegexFromRoute($route) {
        // Split the route by regex capture groups (parts in parentheses)
        $parts = preg_split('/(\([^)]+\))/', $route, -1, PREG_SPLIT_DELIM_CAPTURE);

        $regex = '';
        foreach ($parts as $part) {
            if (preg_match('/^\(.*\)$/', $part)) {
                // This is a parameter pattern, keep as is
                $regex .= $part;
            } else {
                // This is a static part, escape it
                $regex .= preg_quote($part, '/');
            }
        }

        return '/^' . $regex . '$/';
    }
}
