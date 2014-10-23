<?php

abstract class engine_DbFunctions_Abstract {

    protected $theDB = "";
    protected $theType = "";
    protected $Shortcuts = "";

    public function __construct($Db = "") {
        $this->Shortcuts = new engine_Shortcuts();
        
        if ($this->Shortcuts->getDbNeeded() == 0) {
            if ($this->Shortcuts->getDevMod() == 1) {
                echo "Error : Database connexion not enabled. Check your configuration file!";
            }
            exit;
        }
        if ($Db == "") {
            $this->theDB = $this->Shortcuts->getDBConnexion();
        } else {
            $this->theDB = $Db;
        }
    }

    protected function discoverType() {
        if ($this->theType == "") {
            $this->theType = get_class($this);
            $this->theType = strstr($this->theType, '_');
            $this->theType = substr($this->theType, 1);
            $this->theType = lcfirst($this->theType);
        }
        return $this->theType;
    }
    
    public function First($Qty="") {
        return $this->theDB->First($this->discoverType(), $Qty);
    }
    
    public function Last($Qty="") {
        return $this->theDB->Last($this->discoverType(), $Qty);
    }

    public function Save($object) {
        return $this->theDB->Save($object, $this->discoverType());
    }

    public function Find($Id) {
        return $this->theDB->Find($Id, $this->discoverType());
    }

    public function Delete($Id) {
        return $this->theDB->Delete($Id, $this->discoverType());
    }

    public function selectAll() {
        return $this->theDB->selectAll($this->discoverType());
    }
    
    public function Read($Request, $typeObject=""){
        if($typeObject == ""){
            $typeObject = $this->discoverType();
        }
        return $this->theDB->Read($typeObject, $Request);
    }
    
    public function Write($Object, $Request){
        return $this->theDB->Write($Object, $Request);
    }
}

?>
