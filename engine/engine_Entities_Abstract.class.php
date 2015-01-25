<?php

/* Usage specifications */
// Use a relationnal database
// Each table must have and Id field, primary, unique, auto increment
// Use this naming convention :
// SQL table : in lower case . Ex: "books"
// Fields of the SQL table and in the Entity : Uppercase for the first letter. Ex: "Author"
// When you create your Entities class, inheriting from this, once, you don't have to implement the Id field,
// it is already here. Ex:
/*
private $Title = "";
    private $Editor = "";
    private $Description = "";
    private $Type = "";
    private $Author = "";
 */
//On top of that, each field must have a getter and a setter on Java style. Ex :
/*
public function getTitle() {
        return $this->Title;
    }
    public function setTitle($Title) {
        $this->Title = $Title;
    }
    */

abstract class engine_Entities_Abstract {

    private $dataArray = null;
    protected $Id = 0;
    
    public function getId(){
        return $this->Id;
    }
    
    public function setId($Id){
        $this->Id = $Id;
    }

    public function hydrate($value = array()) {
        foreach ($value as $theField => $fieldValue) {
            $requestedFunction = "set" . $theField;
            if (method_exists($this, $requestedFunction)) {
                $this->$requestedFunction($fieldValue);
            }
        }
    }

    public function toArray() {

        if ($this->dataArray == null) {
            $this->dataArray = array();
            
            $myReflection = new ReflectionClass($this);
            $theVars = $myReflection->getProperties();
            
            foreach ($theVars as $value) {
                $getter = "get" . $value->name;
                $this->dataArray[$value->name] = $this->$getter();
            }
        }

        return $this->dataArray;
    }

}

?>
