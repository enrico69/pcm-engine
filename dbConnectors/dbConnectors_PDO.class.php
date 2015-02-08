<?php

class dbConnectors_PDO extends engine_DbConnectors_AbstractType {

    protected function Connexion($Host, $Base, $Id, $Psswd) {
        try {
            $this->theConnexion = new PDO("mysql:host=$Host;dbname=$Base", $Id, $Psswd);
            $this->theConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo "Impossible to connect to the database!";
            exit;
        }
    }

    public function Read($typeObject, $theRequest) {
        $typeObject = $this->conversionNameEntities($typeObject);
        try {
            $req = $this->theConnexion->query($theRequest);
            $count = 0;
            $arrayObjects = "";
            foreach ($req->fetchAll(PDO::FETCH_CLASS, $typeObject) as $currentObject) {
                $arrayObjects[$count] = $currentObject;
                $count++;
            }
            $req->closeCursor();
            return($arrayObjects);
        } catch (Exception $e) {
            $message = "";
            if ($this->ShortCuts->getFatalError() == 1) {
                $message = "<br><strong>" . $e . "</strong>";
            }
            if ($this->ShortCuts->getFatalError() == 0) {
                $this->ShortCuts->setFatalError(1);
                $erreur = new controllers_Error();
                $erreur->start('<strong style="color:red;">Impossible to read the record!</strong><br/><br/>' . $message);
            }
        }
    }

    public function Write($Object, $theRequest) {
        try {
            $sql = $this->theConnexion->prepare($theRequest);

            $reflectionClass = new ReflectionClass($Object);
            $objectVars = $reflectionClass->getProperties();

            foreach ($objectVars as $variable) {
                $requestCopy = $theRequest;
                $matchesQty = substr_count($requestCopy, $variable->getName());

                if ($matchesQty) {
                    for ($count = 0; $count < $matchesQty; $count++) {
                        $charPos = strpos($requestCopy, ":" . $variable->getName());
                        if ($charPos) {
                            $charPos = $charPos + strlen($variable->getName()) + 1;
                            $theChar = substr($requestCopy, $charPos, 1);
                            if ($theChar == " " || $theChar == "," || $theChar == ")" || $theChar == null) {
                                $fonction = "get" . $variable->getName();
                                $sql->bindValue($variable->getName(), $Object->$fonction(), PDO::PARAM_STR);
                                $Object++;
                            }
                            $requestCopy = substr($requestCopy, $charPos);
                        }
                    }
                }
            }
            $this->theConnexion->beginTransaction();
            $sql->execute();
            $lastID = $this->theConnexion->lastInsertId();
            $this->theConnexion->commit();
            if($lastID == 0){
                return true;
            }else{
                return ($lastID);
            }
        } catch (Exception $e) {
            $message = "";
            if ($this->ShortCuts->getFatalError() == 1) {
                $message = "<br><strong>" . $e . "</strong>";
            }
            if ($this->ShortCuts->getFatalError() == 0) {
                $this->ShortCuts->setFatalError(1);
                $erreur = new controllers_Error();
                $erreur->start('<strong style="color:red;">Impossible to write the record!</strong><br/><br/>' . $message);
            }
        }
    }

    public function Find($Id, $typeObject) {
        $sqlRequest = "SELECT * FROM " . $typeObject . " WHERE Id = " . $Id . " LIMIT 1";
        $results = $this->Read($typeObject, $sqlRequest);
        if ($results) {
            return $results[0];
        } else {
            return false;
        }
    }
    
    public function Delete($Id, $typeObject) {
        $sqlRequest = "DELETE FROM " . $typeObject . " WHERE Id = " . $Id . " LIMIT 1";
        
        $typeObject = $this->conversionNameEntities($typeObject);
        $objet = new $typeObject();
        $objet->setId($Id);
        
        $this->Write($objet, $sqlRequest);
        return true;
    }
    
    public function selectAll($typeObject) {
        $sqlRequest = "SELECT * FROM " . $typeObject;
        $results = $this->Read($typeObject, $sqlRequest);
        if ($results) {
            return $results;
        } else {
            return false;
        }
    }
    
    public function First($typeObject, $Qty="") {
        $sqlAdd = " LIMIT 1 ";
        if($Qty != ""){
            $sqlAdd = " LIMIT $Qty ";
        }
        $sqlRequest = "SELECT * FROM " . $typeObject . " ORDER BY Id ASC" . $sqlAdd;

        $results = $this->Read($typeObject, $sqlRequest);
        if ($results) {
            return $results;
        } else {
            return false;
        }
    }
    
    public function Last($typeObject, $Qty="") {
        $sqlAdd = " LIMIT 1 ";
        if($Qty != ""){
            $sqlAdd = " LIMIT $Qty ";
        }
        $sqlRequest = "SELECT * FROM " . $typeObject . " ORDER BY Id DESC" . $sqlAdd;

        $results = $this->Read($typeObject, $sqlRequest);
        if ($results) {
            return $results;
        } else {
            return false;
        }
    }

    public function Save($Object, $typeObject) {
        $requestStatus = "";

        //Check if the entity already exists :
        if ($this->Find($Object->getId(), $typeObject)) {
            $requestStatus = "UPDATE";
        } else {
            $requestStatus = "INSERT";
        }
        //Create the SQL request regarding this existence or not...
        $sqlRequest = $this->createRequest($requestStatus, $Object, $typeObject);
        //Updating or Inserting
        return $this->Write($Object, $sqlRequest);
    }

    protected function createRequest($statut, $object, $typeObject) {
        $sqlRequest = "";
        $reflectionClass = new ReflectionClass($object);
        $objectVars = $reflectionClass->getProperties();

        if ($statut == "UPDATE") {
            $sqlRequest = "UPDATE " . $typeObject . " SET";
            foreach ($objectVars as $attribut) {
                if ($attribut->getName() != "Id") {
                    $sqlRequest = $sqlRequest . " " . $attribut->getName() . "=:" . $attribut->getName() . ",";
                }
            }
            $sqlRequest = substr($sqlRequest, 0, $sqlRequest - 1);
            $sqlRequest = $sqlRequest . " WHERE Id = " . $object->getId();
        } else if ($satut == "INSERT") {
            $sqlRequest = "INSERT INTO " . $typeObject . " VALUES('', ";
            foreach ($objectVars as $attribut) {
                if ($attribut->getName() != "Id") {
                    $sqlRequest = $sqlRequest . ":" . $attribut->getName() . ",";
                }
            }
            $sqlRequest = substr($sqlRequest, 0, $sqlRequest - 1);
            $sqlRequest = $sqlRequest . ")";
        }
        return $sqlRequest;
    }

}

?>