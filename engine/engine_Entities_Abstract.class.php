<?php

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
