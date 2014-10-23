<?php

class engine_Shortcuts {

    public function getSiteTitle() {
        return $GLOBALS['Application']->getsiteTitle();
    }

    public function getUrlSite() {
        return $GLOBALS['Application']->geturlSite();
    }

    public function getAdminEmail() {
        return $GLOBALS['Application']->getadminEmail();
    }

    public function getDevMod() {
        return $GLOBALS['Application']->getdevMod();
    }

    public function getDbErrors() {
        return $GLOBALS['Application']->getdbErrors();
    }

    public function getPublicDIR() {
        return $GLOBALS['Application']->getpublicDIR();
    }

    public function getFatalError() {
        return $GLOBALS['Application']->getfatalError();
    }

    public function getDbNeeded() {
        return $GLOBALS['Application']->getdbNeeded();
    }

    public function getGrantedIP() {
        return $GLOBALS['Application']->getgrantedIP();
    }

    public function getDbInfos() {
        return $GLOBALS['Application']->getdbInfos();
    }
    
    public function getDbConnexion() {
        return $GLOBALS['Application']->getDBConnexion();
    }

    public function setAdminEmail($adminEmail) {
        $GLOBALS['Application']->setAdminEmail($adminEmail);
    }

    public function setFatalError($fatalError) {
       $GLOBALS['Application']->setfatalError($fatalError);
    }
}
