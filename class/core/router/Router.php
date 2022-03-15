<?php

namespace Core\Router;

class Router
{
    private $url;
    private $routes = [];
    private $namedRoutes = [];

    public function __construct($url)
    {
        $this->url = $url;
    }
    /**
     * get() use $_GET['page_name'] as params, the name of the controler and required params to access page.
     * Ex:  $router->get('/slug/:id', "Slug#show"); --> is accessible at : http://localhost/slug/35, call the controler "SlugController.php".
     *
     * @param String $path
     * @param String $callable
     * @param String $name
     * @return route + view + controller
     */
    public function get($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    /**
     * post() return params send by $_POST['page_name'].
     * Ex: $router->post('/login/:id'); --> return $_POST['idPost] at url http://localhost/login/32
     *
     * @param String $path
     * @param String $callable
     * @param String $name
     * @return route + view + controller
     */
    public function post($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    /**
     * add
     *
     * @param  mixed $path
     * @param  mixed $callable
     * @param  mixed $name
     * @param  mixed $method
     *
     * @return $route
     */
    private function add($path, $callable, $name, $method)
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;

        if (is_string($callable) && $name === null) {
            $name = $callable;
        }
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    /**
     * run
     * Verify if the request method is correct or return exception..
     * @return void
     */
    public function run()
    {
        try {
            if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
                throw new RouterException('REQUEST_METHOD doesn\'t exist.');
            }

            foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
                if ($route->match($this->url)) {
                    return $route->call();
                }
            }

            throw new RouterException('No matching routes');
        } catch (RouterException $th) {
            $result['error'] = true;
            $result['message'] = $th->getMessage();
            $req = req_view(['bloc/ErrorMessage']);
            foreach ($req as $req) {
                require $req;
            }
        }
    }

    /**
     * url
     * Verify if the route exist or return exception.
     * @param  mixed $name
     * @param  mixed $params
     *
     * @return void
     */
    public function url($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matches this name');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }
}
