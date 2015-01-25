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
    //Asked Controler
    private $theController = "";
    //DevMod
    private $devMod = 1;
    //Url Mode
    private $urlMode = 0;

    public function __construct() {

        // 1- Load Application parameters
        if (!file_exists('config/settings.php')) {
            echo "Unable to load configuration file";
            exit;
        }
        require_once 'config/settings.php';

        // 2- Recording parameters
        
        $dummyShortcuts = new engine_Shortcuts();
        
        $dummyShortcuts->setDbInfos($dbLogin);
        $dummyShortcuts->setSiteTitle($siteTitle);
        $dummyShortcuts->setUrlSite($urlSite);
        $dummyShortcuts->setAdminEmail($adminEmail);
        $dummyShortcuts->setDevMod($devMod);
        $dummyShortcuts->setDbErrors($dbErrors);
        $dummyShortcuts->setPublicDIR($publicDIR);
        $dummyShortcuts->setDbNeeded($dbRequired);
        $dummyShortcuts->setGrantedIP($grantedIP);
        $dummyShortcuts->setPersoParams($persoParams);
   
        $this->urlMode = $urlMode;
        $this->devMod = $devMod;
        
        $this->consoleOutput("Checking DB parameters");
        if ($dbRequired == 1) {
            $this->consoleOutput("DB needed");
            $DBClass = "dbConnectors_" . ucfirst($dbLogin['DBType']);
            $this->DBConnexion = new $DBClass($dbLogin);
            $dummyShortcuts->setDbConnexion($this->DBConnexion);
        } else {
            $this->consoleOutput("DB not needed");
        }

        $this->consoleOutput("Dev mod enabled");

        //3- Checkins some data...
        
        //IP:
        $this->consoleOutput("Checking IPs...");
        
        if (is_array($grantedIP)) {
            if ($grantedIP[0] != "*") {
                $this->consoleOutput("Access is restricted");
                $accesGranted = false;
                foreach ($grantedIP as $value) {
                    if ($_SERVER["REMOTE_ADDR"] == $value && $accesGranted == false) {
                        $accesGranted = true;
                        $this->consoleOutput("Your IP Adress matches with the authorized list");
                    }
                }

                if (!$accesGranted) {
                    header("HTTP/1.1 403 Unauthorized");
                    echo "Forbidden Access (1) - ";
                    $this->consoleOutput("Your current IP is : " . $_SERVER["REMOTE_ADDR"]);
                    exit;
                }
            } else {
                $this->consoleOutput("Access is not restricted");
            }
        } else {
            $this->consoleOutput("grantedIP is not an array");
            $this->consoleOutput("Forbidden Access (2)");
            header("HTTP/1.1 403 Unauthorized");
            exit;
        }

        //counter to check instances qty (max 1) :
        $this->consoleOutput("Singleton checking");
        self::$theCounter++;
        if (self::$theCounter > 1) {
            $this->consoleOutput("Configuration Error : to much instance of Application");
            header("HTTP/1.1 403 Unauthorized");
        }
        
        //Creating the error controller
        $this->error = new controllers_Error();
    }
    /* END OF THE CONSTRUCTOR */
    
    
    
    
    
    
    

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
            } else if ($this->urlMode == 1) { //Route Mode
                $this->consoleOutput("Route mod selected");
                $moduleRoute = new modules_Routes($askedURL, $URL);
                $dataURL = $moduleRoute->getInfos();
                $this->theController = "controllers_" . $dataURL['Controler'];
                $this->Request = $dataURL['Method'];
            } else if ($this->urlMode == 2) { //Personnal routes Mode
                $this->consoleOutput("Personnal route mod selected");
                $moduleRoute = new modules_MyRoutes($askedURL, $URL);
                $dataURL = $moduleRoute->getInfos();
                $this->theController = "controllers_" . $dataURL['Controler'];
                $this->Request = $dataURL['Method'];
            } else { //If we get there, there is a serious problem...
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

    public function consoleOutput($msg) {
        if ($this->devMod == 1) {
            echo $msg . "<br/>";
        }
    }

}