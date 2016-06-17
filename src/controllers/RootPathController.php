<?php

class RootPathController extends CoreController
{
    public function IndexAction()
    {
        echo 'HERE';
        var_dump(new \DateTime());
        $this->renderView('root_path', []);
    }
}
