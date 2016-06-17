<?php

class RequestHandler
{
    private $_url;
    private $_app;

    public function __construct(AppCore $app)
    {
        $this->_url = $app->getUrl();
        $this->_app = $app;
    }


    public function treatRequest()
    {
        $routeSearch = $this->_app->router->findRouteFromUrl($this->_url);
        if ($routeSearch['match'] === true)
        {
            $this->resolveRequest($routeSearch['route']);
        }
        else
        {
            $this->_app->template->renderView('error404', array(
                'url' => $this->_url,
            ));
        }
    }

    private function resolveRequest($route)
    {
        $exRes = explode('::', $route['resolve']);
        if (count($exRes) == 2)
        {
            $controller = $exRes[0] . 'Controller';
            $action = $exRes[1] . 'Action';
            $resolution = new $controller($this->_app);
            if (method_exists($controller, $action))
            {
                call_user_func_array(array($resolution, $action), $route['arguments']);
            }
        }
    }

}
