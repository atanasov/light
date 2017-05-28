<?php

namespace Light;

class Request
{
    /**
     * Fetch the request URI.
     *
     * @return string
     */
    public static function uri()
    {
        //return trim(
          //  parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/' );
        return $_SERVER['PATH_INFO'] ?? '/';
    }

    /**
     * Fetch the request method.
     *
     * @return string
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Fetch the request data.
     *
     * @return string
     */
    public static function getInput()
    {
        return $_REQUEST;
    }
    
    /**
     * Fetch the GET data.
     *
     * @return string
     */
    public static function getGet()
    {
        return $_GET;
    }

    /**
     * Fetch the POST data.
     *
     * @return string
     */
    public static function getPost()
    {
        return $_POST;
    }        
}
