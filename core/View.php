<?php

namespace app\core;

class View
{
    public string $title = '';

    // Render a string into the layout
    public function renderViewContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderView($view, $params = [])
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();
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

    public function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}
