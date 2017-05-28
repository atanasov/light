<?php

namespace Light;

use Light\Exceptions\RouteNotFoundException;

class App
{
    protected $container;

    public function __construct()
    {
        $this->container = new Container([
            'router' => function () {
                return new Router;
            },
            'request' => function () {
                return new Request;
            },
            'response' => function () {
                return new Response;
            }
        ]);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function get($uri, array $handler)
    {
        $this->container->router->addRoute($uri, $handler, ['GET']);
    }

    public function post($uri, array $handler)
    {
        $this->container->router->addRoute($uri, $handler, ['POST']);
    }

    public function map($uri, array $handler, array $methods = ['GET'])
    {
        $this->container->router->addRoute($uri, $handler, $methods);
    }

    public function run()
    {
        $router = $this->container->router;
        $uri = $this->container->request->uri();
        $router->setPath($uri);

        try {
            $response = $router->getResponse();
            $params = $router->getParams();
        } catch (RouteNotFoundException $e) {
            if ($this->container->has('errorHandler')) {
                $response = $this->container->errorHandler;
                $params = [];
            } else {
                return;
            }
        }
//         var_dump(1,$this->process($response));
// die();
        return $this->respond($this->process($response, $params));
    }

// WORKING
//     protected function process(array $callable, array $params)
//     {
        
//         $controller = $callable[0];
//         $method = $callable[1];
//         $response = $this->container->response;

//         if (is_array($callable)) {
//             if (!is_object($callable[0])) {
//                 $callable[0] = new $controller;
//             }
// // var_dump($callable); die();
//             return call_user_func_array([$controller, $method], $params);
//         }

//         return $callable($response);
//     }


    protected function process($callable,array $params)
    {

        $response = $this->container->response;
        $request = $this->container->request;
        if (is_array($callable)) {
            if (!is_object($callable[0])) {
                $callable[0] = new $callable[0];
            }
                    // var_dump($callable); die();
            // call userdefined controller function
            return call_user_func_array($callable, [$request, $response, $params]);
        }

        return $callable($response);
    }

    protected function respond($response)
    {
    // var_dump($response);
    //     die();
        if (!$response instanceof Response) {
            echo $response;
            return;
        }

        header(sprintf(
            'HTTP/%s %s %s',
            '1.1',
            $response->getStatusCode(),
            ''
        ));

        foreach ($response->getHeaders() as $header) {
            header($header[0] . ': ' . $header[1]);
        }

        echo $response->getBody();
    }
}
