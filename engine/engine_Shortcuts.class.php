<?php

class engine_Shortcuts {

    private static $SiteTitle = "";
    private static $UrlSite = "";
    private static $AdminEmail = "";
    private static $DevMod = 0;
    private static $DbErrors = "";
    private static $PublicDIR = "";
    private static $FatalError = "";
    private static $DbNeeded = "";
    private static $GrantedIP = "";
    private static $DbInfos = "";
    private static $DbConnexion = "";
    private static $persoParams = "";

    public static function getSiteTitle() {
        return self::$SiteTitle;
    }

    public static function getUrlSite() {
        return self::$UrlSite;
    }

    public static function getAdminEmail() {
        return self::$AdminEmail;
    }

    public static function getDevMod() {
        return self::$DevMod;
    }

    public static function getDbErrors() {
        return self::$DbErrors;
    }

    public static function getPublicDIR() {
        return self::$PublicDIR;
    }

    public static function getFatalError() {
        return self::$FatalError;
    }

    public static function getDbNeeded() {
        return self::$DbNeeded;
    }

    public static function getGrantedIP() {
        return self::$GrantedIP;
    }

    public static function getDbInfos() {
        return self::$DbInfos;
    }

    public static function getDbConnexion() {
        return self::$DbConnexion;
    }

    public static function getPersoParams($key) {
        return self::$persoParams[$key];
    }

    public static function setSiteTitle($SiteTitle) {
        self::$SiteTitle = $SiteTitle;
    }

    public static function setUrlSite($UrlSite) {
        self::$UrlSite = $UrlSite;
    }

    public static function setAdminEmail($AdminEmail) {
        self::$AdminEmail = $AdminEmail;
    }

    public static function setDevMod($DevMod) {
        self::$DevMod = $DevMod;
    }

    public static function setDbErrors($DbErrors) {
        self::$DbErrors = $DbErrors;
    }

    public static function setPublicDIR($PublicDIR) {
        self::$PublicDIR = $PublicDIR;
    }

    public static function setFatalError($FatalError) {
        self::$FatalError = $FatalError;
    }

    public static function setDbNeeded($DbNeeded) {
        self::$DbNeeded = $DbNeeded;
    }

    public static function setGrantedIP($GrantedIP) {
        self::$GrantedIP = $GrantedIP;
    }

    public static function setDbInfos($DbInfos) {
        self::$DbInfos = $DbInfos;
    }

    public static function setDbConnexion($DbConnexion) {
        self::$DbConnexion = $DbConnexion;
    }

    public static function setPersoParams($persoParams) {
        self::$persoParams = $persoParams;
    }

    public function consoleOutput($msg) {
        if (self::$DevMod == 1) {
            echo $msg . "<br/>";
        }
    }

}
