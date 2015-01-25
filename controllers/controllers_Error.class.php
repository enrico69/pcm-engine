<?php

class controllers_Error {

    private $thePage = "";
    private $Shortcuts = "";

    public function __construct() {
        $this->thePage = new views_Page();
        $this->Shortcuts = new engine_Shortcuts();
    }

    public function start($Message, $Error = "") {
        $myMeta = array('Title' => "Error...", 'Index' => 0);
        if ($Error != ""){
            $httpHeaderModule = new modules_HTTP_Header();
            $theHeader = $httpHeaderModule->getHeader($Error);
            if($theHeader){
                header($theHeader);
            }else{
                $this->Shortcuts->consoleOutput('<p style="color:red; font-weight:bold">The header you asked in the Error contoller does not exist!</p>');
            }
        }
        //Note : by default your message is displayed in the global view (layout). But you can specify a template...
        $this->thePage->display($Message, $myMeta);
        exit;
    }
}
?>

