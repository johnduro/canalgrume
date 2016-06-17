<?php

class AppCore
{
    private $_url;
    private $_routes;
    private $_views;

    public $template;
    public $router;

    public function __construct($url, array $routes, array $views)
    {
        $this->_url = $url;
        $this->_routes = $routes;
        $this->_views = $views;

        $this->template = new CoreTemplate($this);
        $this->router = new CoreRouter($this);
    }

    public function getRoutes()
    {
        return $this->_routes;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function getViews()
    {
        return $this->_views;
    }

    public function generateLink($route, array $arguments = [])
    {
        $this->router->generateLink($route, $arguments);
    }
}
