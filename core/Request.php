<?php

namespace app\core;

class Request {

    public function getPath(){
        // Get request infos from $_SERVER
        //  ["REQUEST_URI"]=>  string(1) "/", No "PATH_INFO"
        //    ["REQUEST_URI"]=> string(11) "/user?q=que", ["PATH_INFO"]=>  string(5) "/user"
        $path = $_SERVER['REQUEST_URI'] ?? '/'; 
        $position = strpos($path, '?');
        if ($position === false) return $path;

        return substr($path, 0, $position);
    }

    public function getMethod(){
        // ["REQUEST_METHOD"]
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}