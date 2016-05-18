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

    private function findRouteFromUrl()
    {
        $ret = array(
            'match' => false,
            'route' => null,
        );

        echo '<pre>';
        foreach ($this->routes as $name => $route)
        {
            echo "ROUTE - " . $name . " - :<br/>"; //TODELETE
            echo "PATH :: " . $route["path"] . "<br/>";//TODELTE

            //get args -- \/(\{[^\}]+\})\/? -- DONE
            //replace args with pattern (.*)
            //transform all path in pattern
            //compare url with pattern
            //if match return true

            $matches = array();

            if (($nbMatch = preg_match_all("/\/(\{[^\}]+\})\/?/", $route['path'], $matches)) > 0)
            {
                echo 'MATCHED ::<br/>';
                var_dump($matches);
                echo '<br/>';

            }
        }
        echo '</pre>';

        return ($ret);
    }

    public function treatRequest()
    {
        echo 'TREAT REQUEST : <br/>';//TODELETE
        var_dump($this->routes);//TODELETE
        echo '<br/>';//TODELETE

        $routeSearch = $this->findRouteFromUrl();

        var_dump($routeSearch);//TODELETE
    }
}
