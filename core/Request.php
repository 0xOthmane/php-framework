<?php

namespace app\core;

class Request
{

    public function getPath()
    {
        // Get request infos from $_SERVER
        //  ["REQUEST_URI"]=>  string(1) "/", No "PATH_INFO"
        //    ["REQUEST_URI"]=> string(11) "/user?q=que", ["PATH_INFO"]=>  string(5) "/user"
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) return $path;

        return substr($path, 0, $position);
    }

    public function method()
    {
        // ["REQUEST_METHOD"]
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(){
        return $this->method()==='get';
    }

    public function isPost(){
        return $this->method()==='post';
    }


    public function getBody()
    {
        $body = [];
        if ($this->method() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize input 
            }
        }

        if ($this->method() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS); 
            }
        }
        return $body;
    }
}
