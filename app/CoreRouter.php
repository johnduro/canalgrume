<?php

class CoreRouter
{
    private $_app;
    private $_routes;

    private static $ARGUMENTS_REGEX = "/\/(%[^%]+%)\/?/";

    public function __construct(AppCore $app)
    {
        $this->_routes = &$app->getRoutes();

        $this->_app = $app;
    }

    public function generateLink($route, array $arguments = [])
    {
        $ret = '#';
        if ($this->_routes[$route])
        {
            if (count($arguments) > 0)
            {
                $matches = [];
                if (($nbMatch = $this->routeArgumentsMatch( $this->_routes[$route]['path'], $matches)) > 0)
                {
                    $matchedArgs = $matches[1];
                    $replaceArgs = [];
                    foreach ($matchedArgs as $key => $arg)
                    {
                        $cleanArg = trim(substr($arg, 1, -1));
                        $replaceArgs[$key] = array_key_exists($cleanArg, $arguments) ? $arguments[$cleanArg] : '';
                    }
                    $ret = str_replace($matchedArgs, $replaceArgs, $this->_routes[$route]['path']);
                }
            }
            else
            {
                $ret = $this->_routes[$route]['path'];
            }
        }

        echo $ret;
    }

    public function findRouteFromUrl($url)
    {
        $ret = array(
            'match' => false,
            'route' => null,
        );

        foreach ($this->_routes as $name => $route)
        {
            $matches = array();

            if (($nbMatch = $this->routeArgumentsMatch($route['path'], $matches)) > 0)
            {
                $route['arguments'] = $matches[1];
            }
            else
            {
                $route['arguments'] = [];
            }
            $route['pattern'] = $this->transformRouteInPattern($route);

            $urlMatches = array();
            if (preg_match($route['pattern'], $url, $urlMatches) === 1)
            {
                if (count($route['arguments']) > 0)
                {
                    $arguments = array();
                    $idx = 1;
                    foreach ($route['arguments'] as $argument)
                    {
                        if (isset($urlMatches[$idx]))
                        {
                            $arguments[trim(substr($argument, 1, -1))] = $urlMatches[$idx];
                        }
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

    private function routeArgumentsMatch($path, &$matches)
    {
        return preg_match_all(self::$ARGUMENTS_REGEX, $path, $matches);
    }

    private function transformRouteInPattern($route)
    {
        $pattern = $route['path'];

        if (count($route['arguments']) > 0)
        {
            $idx = 1;
            foreach ($route['arguments'] as $argument)
            {
                $pattern = str_replace($argument, '%' . $idx . '%', $pattern);
            }
        }

        $pattern = '/' . preg_quote($pattern, '/') . '/';

        if (count($route['arguments']) > 0)
        {
            while ($idx > 0)
            {
                $pattern = str_replace('%' . $idx . '%', '([^\/]+)', $pattern);
                $idx--;
            }
        }

        return $pattern;
    }
}
