<?php

abstract class engine_DbFunctions_Abstract {

    protected $theDB = "";
    protected $theType = "";
    protected $Shortcuts = "";
    protected $Error = "";
    protected $Helpers = "";

    public function __construct($Db = "") {
        $this->Shortcuts = new engine_Shortcuts();
        $this->Error = new controllers_Error();
        $this->Helpers = new engine_Helpers();

        if ($this->Shortcuts->getDbNeeded() == 0) {
            $this->Shortcuts->consoleOutput('<p style="color:red; font-weight:bold">Error : Database connexion not enabled. Check your configuration file!</p>');
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

    public function first($Qty = "") {
        return $this->theDB->first($this->discoverType(), $Qty);
    }

    public function last($Qty = "") {
        return $this->theDB->last($this->discoverType(), $Qty);
    }

    public function save($object) {
        return $this->theDB->save($object, $this->discoverType());
    }

    public function find($Id) {
        return $this->theDB->find($Id, $this->discoverType());
    }

    public function delete($Id) {
        return $this->theDB->delete($Id, $this->discoverType());
    }

    public function selectAll() {
        return $this->theDB->selectAll($this->discoverType());
    }

    public function read($Request, $typeObject = "") {
        if ($typeObject == "") {
            $typeObject = $this->discoverType();
        }
        return $this->theDB->Read($Request, $typeObject);
    }

    public function write($Request, $Object="") {
        return $this->theDB->Write($Request, $Object);
    }
    
    public function rawReading($theRequest){
        return $this->theDB->rawReading($theRequest);
    }
    
    public function tableInfos($tableName){
        return $this->theDB->tableInfos($tableName);
    }

    
}



?>
