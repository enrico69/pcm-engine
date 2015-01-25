<?php

class views_Page {

    private $HTMLContent = "";
    protected $Shortcuts = "";
    
    public function __construct() {
        $this->Shortcuts = new engine_Shortcuts();
    }

    public function display($theView, $pageMeta) {
        ob_start();
        include ('views/templates/layout.php');
        $this->HTMLContent = ob_get_clean();
        echo $this->HTMLContent;
    }

}

?>
