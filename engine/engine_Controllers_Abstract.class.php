<?php

abstract class engine_Controllers_Abstract {

    protected $urlRequested = "";
    protected $childName = "";
    protected $verifURL = "";
    protected $moduleMeta = "";
    private $thePage = "";
    protected $Error = "";
    protected $Shortcuts = "";
    protected $Helpers = "";
    protected $vendorLoader = "";

    public function __construct($Request = "") {
        $this->verifURL = new engine_VerifURL();
        $this->thePage = new views_Page();
        $this->moduleMeta = new modules_readMetas();
        $this->Error = new controllers_Error();
        $this->Shortcuts = new engine_Shortcuts();
        $this->Helpers = new engine_Helpers();
        $this->vendorLoader = new modules_Vendor_Loader();
        
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
            $this->thePage->display($response['view'], $response['meta']);
        } else {
            $this->Error->start("Page not found!", "404");
        }
    }
    
    protected function goHome(){ 
        header('Location: '.$this->Shortcuts->getUrlSite());
    }
    
    protected function redirect($where){    
        header('Location: '.$where);
    }
    
    protected function getVendor($className) {
        return $this->vendorLoader->includeClass($className);
    }

    abstract protected function index();
}

?>