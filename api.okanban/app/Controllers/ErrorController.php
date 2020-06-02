<?php

namespace Okanban\Controllers;

class ErrorController extends CoreController {

    public function error404()
    {
        $this->show('not-found');
    }
}