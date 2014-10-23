<?php

/* Static route mode */

class modules_Routes {

    private $URL = null;
    private $tableauURL = null;
    private $hasGET = null;

    public function __construct($Url, $tableauURL) {
        $this->URL = $Url;
        $this->tableauURL = $tableauURL;
        //Looking for a get Parameter ex ?Id=foo
        $tailleURL = count($this->tableauURL) -1;
        
        if (isset($this->tableauURL[$tailleURL][0]) && $this->tableauURL[$tailleURL][0] == "?") {
            $this->hasGET = true;
            $this->tableauURL[$tailleURL] = strstr($this->tableauURL[$tailleURL], '?', true);
            $this->URL = "";
            for($count = 0; $count<=$tailleURL; $count++){
                if($this->tableauURL[$count] == ""){
                    $this->tableauURL[$count] = "/";
                }
                $this->URL = $this->URL . $this->tableauURL[$count];
            }
            $this->tableauURL = explode("/", $this->URL);
        }
    }

    public function getInfos() {
        
        if ($this->URL == "/") {//Homepage
            return array('Home', '');
        } else {//Others
            $file = __DIR__ . "/modules_Routes/modules_Routes.xml";
            $xml = @simplexml_load_file($file);
            if (!$xml) {
                $Error = new controllers_Error();
                $Error->start("Routes file is not a XML file!");
            } else {
                $children = $xml->children();
                foreach ($children as $value) {
                    if ($value->Uri == $this->URL) {
                        $data['Controler'] = (string) $value->Controler;
                        $data['Method'] = (string) $value->Method;
                        if(((string) $value->Get == "false") && ($this->hasGET == true)){
                            return;
                        }
                        return($data);
                    }
                }
                return false;
            }
        }
    }

}

?>
