<?php

class CoreController
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function renderView($name, array $variables)
    {
        $this->app->template->renderView($name, $variables);
    }
}
