<?php

namespace Core\Router;

class Route
{
    private $path;
    private $callable;
    private $matches = [];
    private $params = [];

    /**
     * __construct
     * Constructor for the url access.
     * @param  mixed $path
     * @param  mixed $callable
     *
     * @return void
     */
    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * with
     * Permit to  chain method like object->methodA->methodA...[...]
     * @param  mixed $param
     * @param  mixed $regex
     *
     * @return Object
     */
    public function with($param, $regex)
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    /**
     * match
     * Verify if url contain valid parameters
     * @param  mixed $url
     *
     * @return void
     */
    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * paramMatch
     * Return regex for Slug.
     * @param  mixed $match
     *
     * @return void
     */
    private function paramMatch($match)
    {
        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }

        return '([^/]+)';
    }

    /**
     * call
     * Return the controller name.
     * @return $controller
     */
    public function call()
    {
        if (is_string($this->callable)) {
            $params = explode('#', $this->callable);
            $controller = "\\Controller\\" . $params[0] . "Controller";
            $controller = new $controller();
            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    /**
     * getUrl
     * Return parameters for the url validation.
     * @param  mixed $params
     *
     * @return void
     */
    public function getUrl($params)
    {
        $path = $this->path;
        foreach ($params as $k => $v) {
            $path = str_replace(":$k", $v, $path);
        }
        return $path;
    }
}
