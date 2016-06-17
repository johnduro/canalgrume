<?php

class CoreTemplate
{
    private $_app;
    private $_views;

    public function __construct(AppCore $app)
    {
        $this->_views = &$app->getViews();

        $this->_app = $app;
    }

    public function renderView($name, array $variables)
    {
        if (isset($this->_views[$name]))
        {
            $finalView = $this->getViewsList($this->_views[$name]);
            $this->finalRendering($finalView, $variables);
        }
        else
        {
            throw new Exception('View [' . $name . '] to extends does not exist in configuration');
        }
    }

    private function getViewsList(array $view, array $toExtends = [])
    {
        if (count($toExtends) > 0)
        {
            $this->mergeToExtends($view, $toExtends);
        }

        if (isset($view['extends']))
        {
            return $this->getExtension($view);
        }

        return $this->cleanViewList($view);
    }

    private function getExtension(array &$view)
    {
        $toExtends = [];

        $expldExtend = explode('::', $view['extends']);
        $viewToExtend = '';

        if (count($expldExtend) > 1)
        {
            $viewToExtend = trim($expldExtend[0]);
            $placeHolder = trim($expldExtend[1]);
            $toExtends[$placeHolder] = $view['views'];
        }
        else
        {
            $viewToExtend = $view['extends'];
            foreach ($view['views'] as $singleView)
            {
                $expldSingle = explode('::', $singleView);
                if (count($expldSingle) > 1)
                {
                    $toExtends[$expldSingle[0]] = $expldSingle[1];
                }
            }
        }

        if (isset($this->_views[$viewToExtend]))
        {
            $parentView = $this->_views[$viewToExtend];
            $this->mergeVariables($parentView, $view);

            return $this->getViewsList($parentView, $toExtends);
        }
        else
        {
            throw new Exception('View [' . $viewToExtend . '] to extends does not exist in configuration');
        }
    }

    private function mergeToExtends(array &$view, array &$toExtends)
    {
        foreach ($toExtends as $placeHolder => $viewToPlace)
        {
            foreach ($view['views'] as $key => $sView)
            {
                if ($this->matchePlaceHolder($sView, $placeHolder))
                {
                    if (is_array($viewToPlace))
                    {
                        $head = array_slice($view['views'], 0, $key);
                        $tail = array_slice($view['views'], $key);
                        $view['views'] = array_merge($head, $viewToPlace, $tail);
                    }
                    else
                    {
                        $view['views'][$key] = $viewToPlace;
                    }
                    break ;
                }
            }
        }
    }

    private function mergeVariables(array &$parentView, array &$view)
    {
        if (isset($parentView['variables']) || isset($view['variables']))
        {
            if (isset($parentView['variables']) && isset($view['variables']))
            {
                $parentView['variables'] = array_merge($parentView['variables'], $view['variables']);
            }
            elseif (isset($view['variables']))
            {
                $parentView['variables'] = $view['variables'];
            }
        }
    }

    private function matchePlaceHolder($str, $placeHolder)
    {
        if (strpos($str, '%') !== false)
        {
            $str = trim($str);
            if ($str[0] === '%' && substr($str, -1) === '%')
            {
                $str = trim(substr($str, 1, -1));
                if ($str === $placeHolder)
                {
                    return true;
                }
            }
        }

        return false;
    }

    private function cleanViewList(array &$view)
    {
        foreach ($view['views'] as $key => $sView)
        {
            if (strpos($sView, '%') !== false)
            {
                unset($view['views'][$key]);
            }
        }

        return $view;
    }

    private function finalRendering(array &$view, array &$variables)
    {
        $toPrint = [];

        foreach ($view['views'] as $name)
        {
            if (file_exists(ROOT . DS . 'src' . DS . 'views' . DS . $name . '.html'))
            {
                $toPrint[] = ROOT . DS . 'src' . DS . 'views' . DS . $name . '.html';
            }
            else if (file_exists(ROOT . DS . 'app' . DS . 'src' . DS . 'views' . DS . $name . '.html'))
            {
                $toPrint[] = ROOT . DS . 'app' . DS . 'src' . DS . 'views' . DS . $name . '.html';
            }
            else
            {
                throw new Exception('View file [' . $name . '] is missing');
            }
        }

        if (isset($view['variables']) && count($view['variables']) > 0)
        {
            $variables = array_merge($view['variables'], $variables);
        }
        extract($variables);

        $app = $this->_app;

        foreach ($toPrint as $fileName)
        {
            include($fileName);
        }
    }

    /* public function renderView($name, array $variables) */
    /* { */
    /*     extract($variables); */
    /*     $app = $this->_app; */
    /*     if (file_exists(ROOT . DS . 'src' . DS . 'views' . DS . $name . '.html')) */
    /*     { */
    /*         include (ROOT . DS . 'src' . DS . 'views' . DS . $name . '.html'); */
    /*     } */
    /*     else if (file_exists(ROOT . DS . 'app' . DS . 'src' . DS . 'views' . DS . $name . '.html')) */
    /*     { */
    /*         include (ROOT . DS . 'app' . DS . 'src' . DS . 'views' . DS . $name . '.html'); */
    /*     } */
    /*     else */
    /*     { */
    /*         if (file_exists(ROOT . DS . 'src' . DS . 'views' . DS . 'templateNotFound' . '.html')) */
    /*         { */
    /*             include (ROOT . DS . 'src' . DS . 'views' . DS . 'templateNotFound' . '.html'); */
    /*         } */
    /*         else */
    /*         { */
    /*             include (ROOT . DS . 'app' . DS . 'src' . DS . 'views' . DS . 'templateNotFound' . '.html'); */
    /*         } */
    /*     } */
    /* } */
}
