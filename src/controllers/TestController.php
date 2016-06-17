<?php

class TestController extends CoreController
{
    public function IndexAction()
    {
        $this->renderView('testoneView', [
            'variable' => 'depuis controller Index',
            'areg' => 'wut?',
        ]);
    }

    public function WithArgsAction($args)
    {
        $this->app->template->renderView('testoneView', array(
            'variable' => 99,
            'areg' => $args
        ));
    }

    public function WithArgsTwoAction($again)
    {
        $this->renderView('args_two', array(
            'again' => $again,
        ));
    }

    public function WithTwoArgsAction($argz, $path)
    {
        $this->renderView('two_args', array(
            'argz' => $argz,
            'path' => $path,
            'title' => 'override - Title'
        ));
    }
}
