<?php

class dbConnectors_PDO_MYSQL extends engine_DbConnectors_AbstractType {
    /*     * *********** Others methods ***************************************** */

    protected function connexion($Host, $Base, $Id, $Psswd) {
        try {
            $this->theConnexion = new PDO("mysql:host=$Host;dbname=$Base", $Id, $Psswd);
            $this->theConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo "Impossible to connect to the database!";
            exit;
        }
    }

    private function formatError($Error) {
        return "<br/><pre>" . $Error . "</pre>";
    }

    public function tableInfos($tableName) {
        $request = "SHOW FIELDS FROM " . $tableName;
        $theFields = $this->directReading($request);
        return $theFields;
    }

    public function startTransaction() {
        $this->transactionManuallyStarted = 1;
        $this->directWriting("START TRANSACTION");
    }

    public function endTransaction() {
        $this->directWriting("COMMIT");
        $this->transactionManuallyStarted = 0;
    }

    public function cancelTransaction() {
        $this->directWriting("ROLLBACK");
        $this->transactionManuallyStarted = 0;
    }

    /*     * ************* Methods used for reading/writing objects in to the databse ************************************** */

    public function readObject($theRequest, $typeObject, $bindValues = "") {
        $typeObject = $this->conversionNameEntities($typeObject);

        try {
            $sql = $this->theConnexion->prepare($theRequest);

            if ($bindValues != "") {
                foreach ($bindValues as $variable => $value) {
                    $requestCopy = $theRequest;
                    $matchesQty = substr_count($requestCopy, $variable);

                    if ($matchesQty) {
                        for ($count = 0; $count < $matchesQty; $count++) {
                            $charPos = strpos($requestCopy, ":" . $variable);
                            if ($charPos) {
                                $charPos = $charPos + strlen($variable) + 1;
                                $theChar = substr($requestCopy, $charPos, 1);
                                if ($theChar == " " || $theChar == "," || $theChar == ")" || $theChar == null) {
                                    $sql->bindValue($variable, $bindValues[$variable], PDO::PARAM_STR);
                                }
                                $requestCopy = substr($requestCopy, $charPos);
                            }
                        }
                    }
                }
            }
            $sql->execute();

            $count = 0;
            $arrayObjects = "";
            foreach ($sql->fetchAll(PDO::FETCH_CLASS, $typeObject) as $currentObject) {
                $arrayObjects[$count] = $currentObject;
                $count++;
            }
            $sql->closeCursor();

            return($arrayObjects);
        } catch (Exception $e) {
            $message = "";
            if ($this->ShortCuts->getDbErrors() == 1) {
                $message = $this->formatError($e);
            }
            if ($this->ShortCuts->getFatalError() == 0) {
                $this->ShortCuts->setFatalError(1);
                $erreur = new controllers_Error();
                $erreur->start('<strong>Impossible to read the record!</strong>' . $message);
            }
        }
    }

    public function writeObject($theRequest, $Object) {
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
                                if (method_exists($Object, $fonction)) {
                                    $sql->bindValue($variable->getName(), $Object->$fonction(), PDO::PARAM_STR);
                                }
                            }
                            $requestCopy = substr($requestCopy, $charPos);
                        }
                    }
                }
            }


            if ($this->transactionManuallyStarted == 0) {
                $this->theConnexion->beginTransaction();
            }

            $sql->execute();

            if ($this->transactionManuallyStarted == 0) {
                $lastID = $this->theConnexion->lastInsertId();
                $this->theConnexion->commit();
                if ($lastID == 0) {
                    return true;
                } else {
                    return ($lastID);
                }
            }

            return true;
        } catch (Exception $e) {
            if ($this->transactionManuallyStarted == 1) {
                $this->cancelTransaction();
            }

            $message = "";
            if ($this->ShortCuts->getDbErrors() == 1) {
                $message = $this->formatError($e);
            }
            if ($this->ShortCuts->getFatalError() == 0) {
                $this->ShortCuts->setFatalError(1);
                $erreur = new controllers_Error();
                $erreur->start('<strong>Impossible to write the record!</strong>' . $message);
            }
        }
    }

    public function find($Id, $typeObject) {
        $sqlRequest = "SELECT * FROM " . $typeObject . " WHERE Id = :Id LIMIT 1";
        $bindValues = array('Id' => $Id);
        $results = $this->readObject($sqlRequest, $typeObject, $bindValues);
        if ($results) {
            return $results[0];
        } else {
            return false;
        }
    }

    public function delete($Id, $typeObject) {
        $sqlRequest = "DELETE FROM " . $typeObject . " WHERE Id = :Id LIMIT 1";
        $bindValues = array('Id' => $Id);
        $this->write($sqlRequest, $bindValues);
        return true;
    }

    public function selectAll($typeObject) {
        $sqlRequest = "SELECT * FROM " . $typeObject;
        $results = $this->readObject($sqlRequest, $typeObject);
        if ($results) {
            return $results;
        } else {
            return false;
        }
    }

    public function first($typeObject, $Qty = "") {
        $sqlAdd = " LIMIT 1 ";
        if ($Qty != "") {
            $sqlAdd = " LIMIT $Qty ";
        }
        $sqlRequest = "SELECT * FROM " . $typeObject . " ORDER BY Id ASC" . $sqlAdd;

        $results = $this->readObject($sqlRequest, $typeObject);
        if ($results) {
            return $results;
        } else {
            return false;
        }
    }

    public function last($typeObject, $Qty = "") {
        $sqlAdd = " LIMIT 1 ";
        if ($Qty != "") {
            $sqlAdd = " LIMIT $Qty ";
        }
        $sqlRequest = "SELECT * FROM " . $typeObject . " ORDER BY Id DESC" . $sqlAdd;

        $results = $this->readObject($sqlRequest, $typeObject);
        if ($results) {
            return $results;
        } else {
            return false;
        }
    }

    public function save($Object, $typeObject) {
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
        return $this->writeObject($sqlRequest, $Object);
    }

    protected function createRequest($statut, $object, $typeObject) {
        $sqlRequest = "";
        $reflectionClass = new ReflectionClass($object);
        $objectVars = $reflectionClass->getProperties();

        if ($statut == "UPDATE") {
            $sqlRequest = "UPDATE " . $typeObject . " SET";
            foreach ($objectVars as $attribut) {
                if ($attribut->getName() != "Id") {
                    $fonction = "get" . $attribut->getName();
                    if (method_exists($object, $fonction)) {
                        $sqlRequest = $sqlRequest . " " . $attribut->getName() . "=:" . $attribut->getName() . ",";
                    }
                }
            }
            $sqlRequest = substr($sqlRequest, 0, $sqlRequest - 1);
            $sqlRequest = $sqlRequest . " WHERE Id = " . $object->getId();
        } else if ($statut == "INSERT") {
            $sqlRequest = "INSERT INTO " . $typeObject . " VALUES('', ";
            foreach ($objectVars as $attribut) {
                if ($attribut->getName() != "Id") {
                    $fonction = "get" . $attribut->getName();
                    if (method_exists($object, $fonction)) {
                        $sqlRequest = $sqlRequest . ":" . $attribut->getName() . ", ";
                    }
                }
            }
            $sqlRequest = substr($sqlRequest, 0, $sqlRequest - 2);
            $sqlRequest = $sqlRequest . ")";
        }
        return $sqlRequest;
    }

    /*     * ******* Direct Reading / Writing methods *************************** */

    public function directReading($request) {
        $results = array();
        try {
            $req = $this->theConnexion->query($request);
            foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $currentObject) {
                array_push($results, $currentObject);
            }
            return $results;
        } catch (Exception $e) {
            $message = "";
            if ($this->ShortCuts->getDbErrors() == 1) {
                $message = $this->formatError($e);
            }
            if ($this->ShortCuts->getFatalError() == 0) {
                $this->ShortCuts->setFatalError(1);
                $erreur = new controllers_Error();
                $erreur->start('<strong>Impossible to read the record!</strong>' . $message);
            }
        }
    }

    public function directWriting($request) {
        try {
            $this->theConnexion->exec($request);
        } catch (Exception $e) {
            if ($this->transactionManuallyStarted == 1) {
                $this->cancelTransaction();
            }

            $message = "";
            if ($this->ShortCuts->getDbErrors() == 1) {
                $message = $this->formatError($e);
            }
            if ($this->ShortCuts->getFatalError() == 0) {
                $this->ShortCuts->setFatalError(1);
                $erreur = new controllers_Error();
                $erreur->start('<strong>Impossible to write the record!</strong>' . $message);
            }
        }
    }

    /*     * ****** Reading / Writing with auto binding values (for non objects) ************* */

    public function write($theRequest, $bindValues) {
        $sql = $this->theConnexion->prepare($theRequest);

        foreach ($bindValues as $variable => $value) {
            $requestCopy = $theRequest;
            $matchesQty = substr_count($requestCopy, $variable);

            if ($matchesQty) {
                for ($count = 0; $count < $matchesQty; $count++) {
                    $charPos = strpos($requestCopy, ":" . $variable);
                    if ($charPos) {
                        $charPos = $charPos + strlen($variable) + 1;
                        $theChar = substr($requestCopy, $charPos, 1);
                        if ($theChar == " " || $theChar == "," || $theChar == ")" || $theChar == null) {
                            $sql->bindValue($variable, $bindValues[$variable], PDO::PARAM_STR);
                        }
                        $requestCopy = substr($requestCopy, $charPos);
                    }
                }
            }
        }

        if ($this->transactionManuallyStarted == 0) {
            $this->theConnexion->beginTransaction();
        }

        $sql->execute();

        if ($this->transactionManuallyStarted == 0) {
            $lastID = $this->theConnexion->lastInsertId();
            $this->theConnexion->commit();
            if ($lastID == 0) {
                return true;
            } else {
                return ($lastID);
            }
        }
    }

    public function read($theRequest, $bindValues) {
        try {
            $sql = $this->theConnexion->prepare($theRequest);

            if ($bindValues != "") {
                foreach ($bindValues as $variable => $value) {
                    $requestCopy = $theRequest;
                    $matchesQty = substr_count($requestCopy, $variable);

                    if ($matchesQty) {
                        for ($count = 0; $count < $matchesQty; $count++) {
                            $charPos = strpos($requestCopy, ":" . $variable);
                            if ($charPos) {
                                $charPos = $charPos + strlen($variable) + 1;
                                $theChar = substr($requestCopy, $charPos, 1);
                                if ($theChar == " " || $theChar == "," || $theChar == ")" || $theChar == null) {
                                    $sql->bindValue($variable, $bindValues[$variable], PDO::PARAM_STR);
                                }
                                $requestCopy = substr($requestCopy, $charPos);
                            }
                        }
                    }
                }
            }
            $sql->execute();

            $results = array();
            foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $currentObject) {
                array_push($results, $currentObject);
            }
            $sql->closeCursor();

            return($results);
        } catch (Exception $e) {
            $message = "";
            if ($this->ShortCuts->getDbErrors() == 1) {
                $message = $this->formatError($e);
            }
            if ($this->ShortCuts->getFatalError() == 0) {
                $this->ShortCuts->setFatalError(1);
                $erreur = new controllers_Error();
                $erreur->start('<strong>Impossible to read the record!</strong>' . $message);
            }
        }
    }

}

?>