<?php

//Return an array (full data) if a value matches, or just an array with the default title.
//Parameters:
// $classename = Name of the class which call the module : default value : get_class($this)
// $function  = Name of the method wich call the module, default value : __FUNCTION__
//defaultTitle : Optionnal : can return a defautl title
class modules_readMetas {

    private $classname;
    private $function;
    private $defaultTitle;
    private $Uri;

    private function currentPage() {
        if ($this->classname == "controllers_Home") {//Homepage
            return '/Home/';
        } else { //Other pages
            $this->classname = strstr($this->classname, '_');
            $this->classname = substr($this->classname, 1, strlen($this->classname) - 1);

            if ($this->function == "index") {
                return "/" . $this->classname . "/";
            } else {
                return "/" . $this->classname . "/" . $this->function . "/";
            }
        }
    }

    public function readMeta($classname, $function, $defaultTitle = "") {
        
        $this->classname = $classname;
        $this->function = $function;
        $this->defaultTitle = $defaultTitle;
        $this->Uri = $this->currentPage();
        
        $file = __DIR__ . "/metaXML/meta.xml";
        $xml = @simplexml_load_file($file);

        if (!$xml) {
            $data = array('Title' => $this->defaultTitle);
            return $data;
        } else {
            $children = $xml->children();
            foreach ($children as $value) {
                if ($value->Uri == $this->Uri) {
                    $metaData['Title'] = $value->Title;
                    $metaData['Description'] = $value->Description;
                    $metaData['Keywords'] = $value->Keywords;
                    $metaData['Index'] = $value->Index;
                    return($metaData);
                }
            }
            $data = array('Title' => $this->defaultTitle);
            return $data;
        }
    }

}

?>