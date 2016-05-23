<?php

class CoreTemplate
{
    protected $_app;

    public function __construct($app)
    {
        $this->_app = $app;
    }
    public function renderView($name, array $variables)
    {
        extract($variables);
        $app = $this->_app;
        if (file_exists(ROOT . DS . 'src' . DS . 'views' . DS . $name . '.html'))
        {
            include (ROOT . DS . 'src' . DS . 'views' . DS . $name . '.html');
        }
        else if (file_exists(ROOT . DS . 'app' . DS . 'src' . DS . 'views' . DS . $name . '.html'))
        {
            include (ROOT . DS . 'app' . DS . 'src' . DS . 'views' . DS . $name . '.html');
        }
        else
        {
            if (file_exists(ROOT . DS . 'src' . DS . 'views' . DS . 'templateNotFound' . '.html'))
            {
                include (ROOT . DS . 'src' . DS . 'views' . DS . 'templateNotFound' . '.html');
            }
            else
            {
                include (ROOT . DS . 'app' . DS . 'src' . DS . 'views' . DS . 'templateNotFound' . '.html');
            }
        }
    }
}
