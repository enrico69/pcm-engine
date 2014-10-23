<?php

abstract class engine_Controllers_Abstract {

    protected $urlRequested = "";
    protected $childName = "";
    protected $verifURL = "";
    protected $moduleMeta = "";
    private $thePage = "";
    protected $Error = "";
    private $Shortcuts = "";
    
    

    public function __construct($Request = "") {
        $this->verifURL = new engine_VerifURL();
        $this->thePage = new views_Page();
        $this->moduleMeta = new modules_readMetas();
        $this->Error = new controllers_Error();
        $this->Shortcuts = new engine_Shortcuts();
        
        if (empty($Request)) {
            $this->urlRequested = "index";
        } else {
            $this->urlRequested = $Request;
        }
    }

    public function start() {
        $theRequest = $this->urlRequested;
        if($theRequest == "start" || $theRequest == "Start"){
            $this->Error->start("Page not found", "404");
        }
        if (method_exists($this, $theRequest)) {
            $response = $this->$theRequest();
            $this->thePage->Display($response['view'], $response['meta']);
        } else {
            $this->Error->start("Page not found!", "404");
        }
    }
    
    protected function detailArray($data){
        echo "<br/>";
        echo "<pre>";
        echo print_r($data);
        echo "</pre>";
        echo "<br/>";
    }
    
    protected function goHome(){
        header('Status: 301 Moved Permanently', false, 301);      
        header('Location: '.$this->Shortcuts->gethomeURL());
    }
    
    protected function redirect($where){
        header('Status: 301 Moved Permanently', false, 301);      
        header('Location: '.$where);
    }

    abstract protected function index();
}

?>