<?php

abstract class engine_DbConnectors_AbstractType {

    protected $theConnexion = "";
    protected $ShortCuts = "";

    public function __construct($DBInfos) {
        $this->Connexion($DBInfos['Host'], $DBInfos['Base'], $DBInfos['Id'], $DBInfos['Psswd']);
        $this->ShortCuts = new engine_Shortcuts();
    }

    protected function conversionNameEntities($Name) {
        $Name = ucfirst($Name);
        $Name = "entities_" . $Name;
        return ($Name);
    }

    public abstract function tableInfos($tableName);

    protected abstract function connexion($Host, $Base, $Id, $Psswd);

    public abstract function rawReading($request);

    public abstract function read($theRequest, $typeObject);

    public abstract function write($theRequest, $Object);

    public abstract function save($Object, $Type);

    public abstract function first($typeObject, $Qty = "");

    public abstract function last($typeObject, $Qty = "");

    public abstract function find($Id, $typeObject);

    public abstract function delete($Id, $typeObject);

    public abstract function selectAll($typeObject);

    protected abstract function createRequest($statut, $object, $typeObject);
}


?>