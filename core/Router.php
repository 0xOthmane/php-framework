<?php

namespace app\core;

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
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false; // Return a Closure Object
        if ($callback === false) {
            // Application::$app->response->setStatusCode(404);
            $this->response->setStatusCode(404);
            return $this->renderView('_404');
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        // echo call_user_func($callback); // echo the returned string by the callback
        // return call_user_func($callback); call_user_func(): Argument #1 ($callback) must be a valid callback, non-static method app\controllers\SiteController::handleContact() cannot be called statically
        return call_user_func([new $callback[0],$callback[1]]);
    }

    // Render a string into the layout
    public function renderViewContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
        
    }

    public function renderView($view, $params=[])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
        // include_once __DIR__."/../views/{$view}.php"; Anti-pattern
        // include_once Application::$ROOT_DIR."/views/$view.php";
    }

    protected function layoutContent()
    {
        ob_start(); // caching output to browser
        include_once Application::$ROOT_DIR . "/views/layouts/mainLayout.php";
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
