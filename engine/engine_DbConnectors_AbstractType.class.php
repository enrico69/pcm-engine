<?php

abstract class engine_DbConnectors_AbstractType {

    protected $theConnexion = "";
    protected $ShortCuts = "";
    protected $transactionManuallyStarted = 0;

    public function __construct($DBInfos) {
        $this->Connexion($DBInfos['Host'], $DBInfos['Base'], $DBInfos['Id'], $DBInfos['Psswd']);
        $this->ShortCuts = new engine_Shortcuts();
    }

    protected function conversionNameEntities($Name) {
        $Name = ucfirst($Name);
        $Name = "entities_" . $Name;
        return ($Name);
    }
    
    /************* Others methods ******************************************/
    
    public abstract function tableInfos($tableName);

    protected abstract function connexion($Host, $Base, $Id, $Psswd);
    
    public abstract function startTransaction();
    
    public abstract function endTransaction();
    
    public abstract function cancelTransaction();
    
    /*************** Methods used for reading/writing objects in to the databse ***************************************/


    public abstract function readObject($theRequest, $typeObject, $bindValues);

    public abstract function writeObject($theRequest, $Object);

    public abstract function save($Object, $Type);

    public abstract function first($typeObject, $Qty = "");

    public abstract function last($typeObject, $Qty = "");

    public abstract function find($Id, $typeObject);

    public abstract function delete($Id, $typeObject);

    public abstract function selectAll($typeObject);

    protected abstract function createRequest($statut, $object, $typeObject);
    
    /********* Direct Reading / Writing methods ****************************/
    
    public abstract function directReading($request);
    public abstract function directWriting($request);
    
    /******** Reading / Writing non objetcs ********************************/
    public abstract function write($sqlRequest, $bindValues);
    public abstract function read($sqlRequest, $bindValues);
    

}


?>