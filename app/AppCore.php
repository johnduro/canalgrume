<?php

class AppCore
{
    private $_url;
    private $_routes;
    private $_views;

    public $template;

    public function __construct($url, $routes, $views)
    {
        $this->_url = $url;
        $this->_routes = $routes;
        $this->_views = $views;

        $this->template = new CoreTemplate($this);
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
}
