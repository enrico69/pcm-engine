<?php

class engine_Helpers {
    
    protected $managerHeader = null;
    
    public function sendHeader($header){
        if($this->managerHeader == null){
            $this->managerHeader = new modules_HTTP_Header();
        }
        header($this->managerHeader->getHeader($header));
    }

    public function isPOST() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        }
        return false;
    }

    public function isGET() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return true;
        }
        return false;
    }

    public function isPUT() {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            return true;
        }
        return false;
    }

    public function isDELETE() {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            return true;
        }
        return false;
    }

    public function isInt($Val) {
        if (filter_var($Val, FILTER_VALIDATE_INT)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isNumeric($Val) {
        if (preg_match('/^[0-9]+$/', $Val)) {
            return true;
        } else {
            return false;
        }
    }

    public function isEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
    
    public function detailArray($data){
        echo "<br/>";
        echo "<pre>";
        echo print_r($data);
        echo "</pre>";
        echo "<br/>";
    }
}
