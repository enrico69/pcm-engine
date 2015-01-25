<?php

class engine_VerifURL {

    private $URI = "/";
    private $arrayURI = array();
    private $URILength = 0;
    private $Shortcuts = null;

    public function __construct() {
        $this->Shortcuts = new engine_Shortcuts();
        
        $urlSite = explode("/", addslashes($this->Shortcuts->getUrlSite()));
        $serverURI = explode("/", addslashes($_SERVER['REQUEST_URI']));

        $lastPartOfUrlSite = count($urlSite);
        $lastPartOfUrlSite = $urlSite[$lastPartOfUrlSite - 2];

        $readyToTransfer = 0;
        foreach ($serverURI as $value) {
            if ($readyToTransfer == 1 && $value != "") {
                $this->URI = $this->URI . $value . "/";
            }
            if ($value == $lastPartOfUrlSite) {
                $readyToTransfer = 1;
            }
        }
        if ($readyToTransfer == 0) {
            $this->URI = $_SERVER['REQUEST_URI'];
        }
        
        $this->arrayURI = explode("/", $this->URI);
        $this->URILength = count($this->arrayURI);
    }

    public function getURI() {
        return $this->URI;
    }

    public function checkGetData() {
        if (stripos($this->URI, "?")) {
            $error = new controllers_Error404("Url inexistante");
            $error->start();
        }
    }

    public function isSomeoneAfter($URI, $ActionToPerform) {
        $isSomethingAfter = 0;
        $i = -1;
        foreach ($this->arrayURI as $value) {
            $i++;
            if ($value == $URI && $i < (count($this->arrayURI) - 2)) { // Means there is something after
                $isSomethingAfter = 1;
            }
            if ($isSomethingAfter == 1) {
                if($ActionToPerform == "404"){
                    $error = new controllers_Error404("Url inexistante");
                    $error->start();
                }else if($ActionToPerform == "return"){
                    return $this->arrayURI[$i+1];
                }
            }
        }
    }

}

?>
