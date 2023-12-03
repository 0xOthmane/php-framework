<?php

namespace app\core;

use app\core\exceptions\NotFoundException;

class Router
{

    public Request $request;
    public Response $response;
    protected array $routes = [];
    // 2D Array ['get' => [$path=>$callback, ...], 'post' => [$path=>$callback, ...]]

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }


    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false; // Return a Closure Object
        if ($callback === false) {
            // Application::$app->response->setStatusCode(404);
            
            throw new NotFoundException();
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        // echo call_user_func($callback); // echo the returned string by the callback
        // return call_user_func($callback); The argument is called statically, callback must not be a non-static method.
        if (is_array($callback)) {
            /** @var Controller $controller */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            foreach ($controller->getMiddlewares() as  $middleware) {
                $middleware->execute();
            }
            $callback[0] = $controller;
        }
        return call_user_func($callback, $this->request, $this->response);
    }

    // Render a string into the layout
    public function renderViewContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderView($view, $params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
        // include_once __DIR__."/../views/{$view}.php"; Anti-pattern
        // include_once Application::$ROOT_DIR."/views/$view.php";
    }

    protected function layoutContent()
    {
        $layout = Application::$app->layout;
        if (Application::$app->controller) {
            $layout = Application::$app->controller->layout;
        }
        ob_start(); // caching output to browser
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean(); // Get current buffer contents and delete current output buffer
    }

    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}
