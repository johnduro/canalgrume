<?php

class TestController extends CoreController
{
    public function IndexAction()
    {
        echo '<br/>TEST :: INDEX ACTION<br/>';
    }

    public function WithArgsAction($args)
    {
        echo '<br/>TEST :: WITHARGS ACTION<br/>';
        $this->app->template->renderView('testoneView', array(
            'variable' => 99,
            'areg' => $args
        ));
    }

    public function WithArgsTwoAction($again, $middle)
    {
        echo '<br/>TEST :: WITHARGSTWO ACTION<br/>';
    }

    public function WithTwoArgsAction($argz, $path)
    {
        echo '<br/>TEST :: WITHTWOARGS ACTION<br/>';
        echo 'ARGZZZ : ' . $argz . '<br/>';
        echo 'PATH : ' . $path. '<br/>';
    }
}
