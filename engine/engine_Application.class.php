<?php

class engine_Application {
    
    //Error Controler
    private $error = null;
    //Instances counter
    static $theCounter = 0;
    //is Application startedd?
    static $controllerStarted = 0;
    //Requested method
    private $Request = "";
    //Database connexion
    private $DBConnexion = "";
    //Site Title
    private $siteTitle = "";
    //Url home
    private $urlSite = "";
    //Asked Controler
    private $theController = "";
    //Admin's Email
    private $adminEmail = "";
    //DevMod
    private $devMod = 1;
    //Display BDD Errors
    private $dbErrors = 0;
    //Public Directory
    private $publicDIR = "";
    //fatalError encountered
    private $fatalError = 0;
    //Url Mode
    private $urlMode = 0;
    //Required DB connexion
    private $dbNeeded = 0;
    //granted IPs
    private $grantedIP = null;
    //DB infos
    private $dbInfos = null;

    public function __construct() {
        
        $this->consoleOutput("dev mod enabled");
        
        // Preparation :
        $this->error = new controllers_Error();
        
        // 1- Load Application parameters
        if(!file_exists('config/settings.php')){
            $this->consoleOutput("Unable to load configuration file");
            exit;
        }
        require_once 'config/settings.php';

        // 2- Stocking parameters
        $DBClass = "dbConnectors_" . ucfirst($dbLogin['DBType']);
        $this->dbInfos = $dbLogin;
        $this->siteTitle = $siteTitle;
        $this->urlSite = $urlSite;
        $this->adminEmail = $adminEmail;
        $this->devMod = $devMod;
        $this->dbErrors = $dbErrors;
        $this->publicDIR = $publicDIR;
        $this->urlMode = $urlMode;
        $this->dbNeeded = $dbRequired;
        $this->grantedIP = $grantedIP;
        
        //3- Checkins some data...
        
        //IP:
        $this->consoleOutput("Checking IPs...");
        if(is_array($this->grantedIP)){
            if($this->grantedIP[0] != "*"){
                $this->consoleOutput("Access is restricted");
                $accesGranted = false;
                foreach ($this->grantedIP as $value) {
                    if($_SERVER["REMOTE_ADDR"] == $value){
                        $accesGranted = true;
                        $this->consoleOutput("Your IP Adress match with the authorized list");
                        return;
                    }
                    $this->consoleOutput("our IP Adress doesn't match with the authorized list");
                }
                
                if(!$accesGranted){
                    $this->error->start("Forbidden Access", "403");
                    exit;
                }
            }
            $this->consoleOutput("Access is not restricted");
        }else{
            $this->consoleOutput("grantedIP is not an array");
            $this->error->start("Forbidden Access (2)", "403");
            exit;
        }
        
        //DB :
        $this->consoleOutput("Checking DB parameters");
        if($this->dbNeeded == 1){
            $this->consoleOutput("DB needed");
            $this->DBConnexion = new $DBClass($dbLogin);
        }else{
            $this->consoleOutput("DB not needed");
        }
        
        //counter to check instances qty (max 1) :
        $this->consoleOutput("Singleton checking");
        self::$theCounter++;
        if (self::$theCounter > 1) {
            $this->error->start("Configuration Error : to much instance of Application", "406");
       }
    }

    public function start() {
        $this->consoleOutput("Application started");
        // 1- URL pre processing
        $verifURL = new engine_VerifURL();
        $askedURL = strtolower($verifURL->getURI());
        $URL = explode("/", $askedURL);
        
        // 2- Special cases

        if ($URL[1] == "home") { //Prevent direct access to the home controler (SEO friendly)
            $this->error->start("Page not found", "404");
        }

        // 3- Normal cases :
        if ($this->theController == "") {

            // 3-A Normal URL mode
            if ($this->urlMode == 0) {
                $this->consoleOutput("Standard route mod selected");
                // 4- Find the requested controler
                // 4-A Is any requested method?
                if (isset($URL[2]) && (strlen($URL[2]) > 0) && ($URL[2][0] != "?")) {
                    $this->Request = $URL[2];
                }

                // 4- Processing:
                if (strlen($URL[1]) == 0) { //Homepage
                    $this->theController = "controllers_Home";
                } else {
                    //another page than home
                    // GET parameters?
                    if (stripos($this->Request, "?")) {
                        //Lookinf for the method name
                        $this->MethodIsolation();
                    }
                    $this->theController = "controllers_" . ucfirst($URL[1]);
                }
            }else if($this->urlMode == 1) { //Route Mode
                $this->consoleOutput("Route mod selected");
                $moduleRoute = new modules_Routes($askedURL, $URL);
                $dataURL = $moduleRoute->getInfos();
                $this->theController = "controllers_" . $dataURL['Controler'];
                $this->Request = $dataURL['Method'];
            }else if($this->urlMode == 2) { //Personnal routes Mode
                $this->consoleOutput("Personnal route mod selected");
                $moduleRoute = new modules_MyRoutes($askedURL, $URL);
                $dataURL = $moduleRoute->getInfos();
                $this->theController = "controllers_" . $dataURL['Controler'];
                $this->Request = $dataURL['Method'];
            }else{ //If we get there, there is a serious problem...
                $this->error->start("Unable to select the correct route mode!");
                exit;
            }
            
            //Now : checking if the controler file exist
            if (file_exists("controllers/" . $this->theController . '.class.php')) {
                $this->theController = new $this->theController($this->Request);
            } else {
                // or 404
                $this->error->start('Page not found!', '404');
            }
        }
        //5- Here we go...
        $this->consoleOutput("Controler : " . get_class($this->theController));
        $this->consoleOutput("Method (if empty => index) : " . $this->Request);
        if (self::$controllerStarted == 0) {
            self::$controllerStarted++;
            $this->theController->start();
        } else {
            $this->error("Controler already started!", '406');
        }
    }

    private function MethodIsolation() {
        //Saving the request method's name
        $this->Request = strstr($this->Request, '?', true);
    }

    public function exceptions_error_handler($severity, $message, $filename, $lineno) {
        if (error_reporting() == 0) {
            return;
        }
        if (error_reporting() & $severity) { //Error
            throw new ErrorException($message, 0, $severity, $filename, $lineno); //Throw exception
        }
    }
    
    public function consoleOutput($msg){
        if($this->devMod == 1){ 
            echo $msg."<br/>";
        } 
    }
    
    /* Getters and (some) setters */

    function getSiteTitle() {
        return $this->siteTitle;
    }

    function getUrlSite() {
        return $this->urlSite;
    }

    function getAdminEmail() {
        return $this->adminEmail;
    }

    function getDevMod() {
        return $this->devMod;
    }

    function getDbErrors() {
        return $this->dbErrors;
    }

    function getPublicDIR() {
        return $this->publicDIR;
    }

    function getFatalError() {
        return $this->fatalError;
    }

    function getDbNeeded() {
        return $this->dbNeeded;
    }

    function getGrantedIP() {
        return $this->grantedIP;
    }

    function getDbInfos() {
        return $this->dbInfos;
    }

    function setAdminEmail($adminEmail) {
        $this->adminEmail = $adminEmail;
    }

    function setFatalError($fatalError) {
        $this->fatalError = $fatalError;
    }
    
    function getDBConnexion() {
        return $this->DBConnexion;
    }
}

?>