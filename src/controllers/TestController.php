<?php

class TestController extends CoreController
{
    public function IndexAction()
    {
        echo '<br/>TEST :: INDEX ACTION<br/>';
    }

    public function WithArgsAction()
    {
        echo '<br/>TEST :: WITHARGS ACTION<br/>';
    }

    public function WithArgsTwoAction()
    {
        echo '<br/>TEST :: WITHARGSTWO ACTION<br/>';
    }

    public function WithTwoArgsAction()
    {
        echo '<br/>TEST :: WITHTWOARGS ACTION<br/>';
    }
}
