<?php

class AppCore
{
    public $template;

    public function __construct()
    {
        $this->template = new CoreTemplate($this);
    }
}
