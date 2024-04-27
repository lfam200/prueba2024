<?php
namespace App\Infrastructure;

/**
 * Class Router
 *
 * This class handles the routing of HTTP requests.
 */
class Router {

    /**
     * @var array The array of routes.
     */
    private array $routes = [];

    /**
     * Add a GET route.
     *
     * This method adds a GET route to the routes array.
     *
     * @param string $uri The URI of the route.
     * @param callable|array $action The action to perform when the route is accessed.
     */
    public function get($uri, $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    /**
     * Add a POST route.
     *
     * This method adds a POST route to the routes array.
     *
     * @param string $uri The URI of the route.
     * @param callable|array $action The action to perform when the route is accessed.
     */
    public function post($uri, $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    /**
     * Run the router.
     *
     * This method runs the router, performing the action associated with the current URI and request method. If no matching route is found, it sends a 404 response.
     */
    public function run(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            if (is_callable($action)) {
                call_user_func($action);
            } else {
                [ $class, $method ] = $action;
                (new $class)->$method();
            }
        } else {
            header("HTTP/1.1 404 Not Found");
            echo "404 Not Found";
            exit;
        }
    }
}
