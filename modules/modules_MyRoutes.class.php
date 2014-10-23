<?php
class modules_MyRoutes {
    
    private $askedURL = "";
    private $URL = "";
    
    public function __construct($askedURL, $URL) {
        $this->askedURL = $askedURL;
        $this->URL = $URL;
    }
    
    public function getInfos(){
        //Your personnal route system
        //You need to return an array :
        //return array('Controler' => $controler, 'Method' => $method);
    }    
}
