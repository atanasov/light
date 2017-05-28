<?php

namespace Light;

use Light\Exceptions\MethodNotAllowedException;
use Light\Exceptions\RouteNotFoundException;

class Router
{
    protected $path;

    protected $routes = [];

    protected $methods = [];
    
    protected $params = [];

    public function setPath($path = '/')
    {
        $this->path = $path;
    }

    // public function addRoute($uri, $handler, array $methods = ['GET'])
    public function addRoute($pattern, array $callback, array $methods = ['GET'])
    {
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        $this->routes[$pattern] = $callback;
        $this->methods[$pattern] = $methods;
    }
    // {
    //     $this->routes[$uri] = $handler;
    //     $this->methods[$uri] = $methods;
    // }

    // public function execute() {
    //     foreach ($this->routes as $pattern => $callback) {
    //         if (preg_match($pattern, $this->path, $params)) {
    //             array_shift($params);
    //             return call_user_func_array($callback, array_values($params));
    //         }
    //     }
    // }
    public function getParams()
    {
        return $this->params;
    }
    
    public function getResponse()
    {
        foreach ($this->routes as $pattern => $callback) {
            if (preg_match($pattern, $this->path, $params)) {
                array_shift($params);
                $this->params = array_values($params);
                return $this->routes[$pattern];
                // return call_user_func_array($callback, array_values($params));
            }
        }

        if (!isset($this->routes[$this->path])) {
            throw new RouteNotFoundException('No route registered for ' . $this->path);
            // die();
        }

        if (!in_array($_SERVER['REQUEST_METHOD'], $this->methods[$this->path])) {
            throw new MethodNotAllowedException;
        }


        return $this->routes[$this->path];
    }
}
