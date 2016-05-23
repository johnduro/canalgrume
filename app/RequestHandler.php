<?php

class RequestHandler
{
    protected $routes;
    protected $url;

    public $app;

    public function __construct($routes, $url, $app)
    {
        $this->routes = $routes;
        $this->url = $url;
        $this->app = $app;
    }

    private function transformRouteInPattern($route)
    {
        $pattern = $route['path'];
        if ($route['arguments'])
        {
            $idx = 1;
            foreach ($route['arguments'] as $argument)
            {
                $pattern = str_replace($argument, '%' . $idx . '%', $pattern);
            }
        }
        $pattern = '/' . preg_quote($pattern, '/') . '/';
        if ($route['arguments'])
        {
            while ($idx > 0)
            {
                $pattern = str_replace('%' . $idx . '%', '([^\/]+)', $pattern);
                $idx--;
            }
        }
        return $pattern;
    }

    private function findRouteFromUrl()
    {
        $ret = array(
            'match' => false,
            'route' => null,
        );

        foreach ($this->routes as $name => $route)
        {
            $matches = array();

            if (($nbMatch = preg_match_all("/\/(%[^%]+%)\/?/", $route['path'], $matches)) > 0)
            {
                $route['arguments'] = $matches[1];
            }
            else
                $route['arguments'] = null;
            $route['pattern'] = $this->transformRouteInPattern($route);

            $urlMatches = array();
            if (preg_match($route['pattern'], $this->url, $urlMatches) === 1)
            {
                if ($route['arguments'])
                {
                    $arguments = array();
                    $idx = 1;
                    foreach ($route['arguments'] as $argument)
                    {
                        if (isset($urlMatches[$idx]))
                            $arguments[substr($argument, 1, -1)] = $urlMatches[$idx];
                        $idx++;
                    }
                    $route['arguments'] = $arguments;
                }

                $ret['match'] = true;
                $ret['route'] = $route;
                break ;
            }
        }

        return ($ret);
    }

    private function resolveRequest($route)
    {
        $exRes = explode('::', $route['resolve']);
        if (count($exRes) == 2)
        {
            $controller = $exRes[0] . 'Controller';
            $action = $exRes[1] . 'Action';
            $resolution = new $controller($this->app);
            if (method_exists($controller, $action))
            {
                call_user_func_array(array($resolution, $action), $route['arguments']);
            }
        }
    }

    public function treatRequest()
    {
        $routeSearch = $this->findRouteFromUrl();
        if ($routeSearch['match'] === true)
        {
            $this->resolveRequest($routeSearch['route']);
        }
        else
        {
            $this->app->template->renderView('error404', array(
                'url' => $this->url,
            ));
        }
    }
}
