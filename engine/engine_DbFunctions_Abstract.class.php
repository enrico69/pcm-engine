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
    
    /***************Methods used for reading/writing objects in to the databse ***************************************/

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

    public function readObject($Request, $typeObject = "", $bindValues = "") {
        if ($typeObject == "") {
            $typeObject = $this->discoverType();
        }
        return $this->theDB->readObject($Request, $typeObject, $bindValues);
    }

    public function writeObject($Request, $Object) {
        return $this->theDB->Write($Request, $Object);
    }

    //Methods use for direct reading/writing
    
    public function directReading($theRequest){
        return $this->theDB->directReading($theRequest);
    }
    
    public function directWriting($theRequest){
        return $this->theDB->directWriting($theRequest);
    }
    
    // Reading / Writing non objetcs 
    public function write($sqlRequest, $bindValues){
        return $this->theDB->write($sqlRequest, $bindValues);
    }
    public function read($sqlRequest, $bindValues){
        return $this->theDB->read($sqlRequest, $bindValues);
    }
    
    //Others methods
    
    public function tableInfos($tableName){
        return $this->theDB->tableInfos($tableName);
    }
    
    public function startTransaction(){
        return $this->theDB->startTransaction();
    }
    
    public function endTransaction(){
        return $this->theDB->endTransaction();
    }

    public function cancelTransaction(){
        return $this->theDB->cancelTransaction();
    }
    
}



?>
