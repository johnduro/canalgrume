<?php

class RequestHandler
{
    protected $routes;
    protected $url;

    public function __construct($routes, $url)
    {
        $this->routes = $routes;
        $this->url = $url;
    }

    private function transformRouteInPattern($route)
    {
        /* $pattern = '|' . $route['path'] . '|'; */
        $pattern = $route['path'];
        if ($route['arguments'])
        {
            $idx = 1;
            foreach ($route['arguments'] as $argument)
            {
                /* $pattern = str_replace($argument, '([^\/]+)', $pattern); */
                $pattern = str_replace($argument, '%' . $idx . '%', $pattern);
            }
        }
        $pattern = '/' . preg_quote($pattern, '/') . '/';
        if ($route['arguments'])
        {
            /* $idx = 1; */
            while ($idx > 0)
            /* foreach ($route['arguments'] as $argument) */
            {
                $pattern = str_replace('%' . $idx . '%', '([^\/]+)', $pattern);
                $idx--;
                /* $pattern = str_replace($argument, '%' . $idx . '%', $pattern); */
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
            echo '=======================================================<br/>';
            echo "ROUTE - " . $name . " - :<br/>"; //TODELETE
            echo "PATH :: " . $route["path"] . "<br/>";//TODELTE

            //get args -- \/(\{[^\}]+\})\/? -- DONE
            //replace args with pattern (.*) -- DONE
            //transform all path in pattern -- DONE
            //compare url with pattern -- DONE
            //if match return true -- DONE

            $matches = array();

            /* if (($nbMatch = preg_match_all("/\/(\{[^\}]+\})\/?/", $route['path'], $matches)) > 0) */
            if (($nbMatch = preg_match_all("/\/(%[^%]+%)\/?/", $route['path'], $matches)) > 0)
            {
                echo 'MATCHED ::<br/>';
                var_dump($matches);
                echo '<br/>';
                $route['arguments'] = $matches[1];
            }
            else
                $route['arguments'] = null;
            $route['pattern'] = $this->transformRouteInPattern($route);
            echo '<br/>=== ROUTE ARRAY ===<br/>';
            var_dump($route);
            echo '<br/>';



            echo '<br/>=== COMPARE WITH PATTERN ===<br/>';
            $urlMatches = array();
            if (preg_match($route['pattern'], $this->url, $urlMatches) === 1)
            {
                echo '##########################<br/>';
                echo '##      MATCHE !!!      ##<br/>';
                echo '##########################<br/>';
                var_dump($urlMatches);

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
                echo '##########################<br/>';
            }
            echo '<br/>';
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
            $resolution = new $controller($route['arguments']);
            if (method_exists($controller, $action))
            {
                call_user_func_array(array($controller, $action), array());
            }
        }
    }

    public function treatRequest()
    {
        echo '<pre>';
        echo 'TREAT REQUEST : <br/>';//TODELETE
        var_dump($this->routes);//TODELETE
        echo '<br/>';//TODELETE

        $routeSearch = $this->findRouteFromUrl();
        if ($routeSearch['match'] === true)
        {
            $this->resolveRequest($routeSearch['route']);
        }
        else
        {
            //error404
        }

        echo '<br/>_-= RESULT =-_<br/>';
        var_dump($routeSearch);//TODELETE
        echo '</pre>';
    }
}
