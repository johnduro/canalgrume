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

    public function treatRequest()
    {
        echo 'TREAT REQUEST : <br/>';//TODELETE
        var_dump($this->routes);//TODELETE
    }
}
