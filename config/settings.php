<?php

/* pcm-engine Version 1.1 - Eric COURTIAL */
/* Global parameters */

$siteTitle = "My Cool web Site";
$urlSite = "http://pcm-engine.dev/"; //Don't forget the slash "/" at the end

/* Email Admin */
$adminEmail = "";

/** DB * */
$dbLogin = array('DBType' => 'PDO_MYSQL', 'Host' => '127.0.0.1', 'Base' => 'books', 'Id' => 'root', 'Psswd' => 'root');
/* * ***** */

/* Dev Mode */
$devMod = 1; // 0 = Production Mode, 1= Dev Mode 
/* * ******* */

/* Display Bdd Errors */
$dbErrors = 1;
/* * ********** */

/* Default public DIR */
$publicDIR = "www";
/* * ************************ */

/* URL management mode : 
 * 0 : 'Controlers/Method Mode' Ex : www.mysite.com/controlers/method  where controlers is a controler class and method one of his methods
 * 1 : Statics routes mode : see Modules/modules_Routes.xml file
 * 2 : Your own mode. See Modules/modules_MyRoutes.class.php file
 */
$urlMode = 0;
/* * ************************ */

/* is DB required  : 0 = No 1 = Yes */
$dbRequired = 0;
/* * ************************ */

/* granted IP (recommanded in dev mod, but usable when you want) */
$grantedIP = array('*'); //Sperate granted IPs with comma ','. Or  '*' = all IPs granted

/* All your personnal parameters : key=>value */
$persoParams = array();

?>