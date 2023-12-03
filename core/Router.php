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
            return Application::$app->view->renderView($callback);
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
        return Application::$app->view->renderViewContent($viewContent);
    }

    public function renderView($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }


    protected function renderOnlyView($view, $params)
    {
        return Application::$app->view->renderOnlyView($view, $params);
    }
}
