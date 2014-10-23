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

    protected abstract function Connexion($Host, $Base, $Id, $Psswd);

    public abstract function Read($typeObject, $theRequest);

    public abstract function Write($Object, $theRequest);
    
    public abstract function Save($Object, $Type);
    
    public abstract function First($typeObject, $Qty="");

    public abstract function Last($typeObject, $Qty="");

    public abstract function Find($Id, $typeObject);
    
    public abstract function Delete($Id, $typeObject);
    
    public abstract function selectAll($typeObject);

    protected abstract function createRequest($statut, $object, $typeObject);
}

?>