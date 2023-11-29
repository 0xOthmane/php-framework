<?php

namespace app\core;

class Router
{

    public Request $request;
    protected array $routes = [];
    // 2D Array ['get' => [$path=>$callback, ...], 'post' => [$path=>$callback, ...]]

    public function __construct($request)
    {
        $this->request = $request;
    }


    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function resolve()
    {
        $path= $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false; // Return a Closure Object
        if ($callback === false) {
            echo 'Not found.'; exit;
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        echo call_user_func($callback); // echo the returned string by the callback
    }

    public function renderView($view) {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);
        return str_replace('{{content}}', $viewContent, $layoutContent);
        // include_once __DIR__."/../views/{$view}.php"; Anti-pattern
        // include_once Application::$ROOT_DIR."/views/$view.php";
    }

    protected function layoutContent(){
        ob_start(); // caching output to browser
        include_once Application::$ROOT_DIR."/views/layouts/mainLayout.php";
        return ob_get_clean(); // Get current buffer contents and delete current output buffer
    }

    protected function renderOnlyView($view) {
        ob_start(); 
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}
