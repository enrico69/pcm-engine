<?php

abstract class engine_Views_Abstract {

    protected $content = "";
    protected $theView = "";
    protected $Shortcuts = "";


    public function __construct() {
        $this->Shortcuts = new engine_Shortcuts();
    }

    public function getView($theMethod, $theContent = "", $theParameter = "") {
        $this->content = $theContent;
        ob_start();
        $this->$theMethod($theParameter);
        $this->theView = ob_get_clean();
        return($this->theView);
    }

}

?>
