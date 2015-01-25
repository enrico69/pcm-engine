<?php

class controllers_Home extends engine_Controllers_Abstract {

    protected function index() {
        $viewHome = new views_Home();
        $viewHome = $viewHome->getView("home");

        $myMeta = $this->moduleMeta->readMeta(get_class($this), __FUNCTION__, "My Library...");

        return array('view' => $viewHome, 'meta' => $myMeta);
    }

}

?>