<?php

class controllers_Contact extends engine_Controllers_Abstract {

    protected function index() {
        
        $viewContact = new views_Contact();
        if (count($_POST)>0) { //is POST
            if($this->validation()){
                $moduleEmail = new modules_Email();
                if($moduleEmail->sendEmail($GLOBALS['Application']->getAdminEmail(), $_POST['Email'], $_POST['Message'], "Email send from")){
                    $laVue = $viewContact->getView("Success");
                    $myMeta = array('Title' => "Succès...", 'Index' => 0);
                }else{
                    $laVue = $viewContact->getView("Error");
                    $myMeta = array('Title' => "Erreur...", 'Index' => 0);
                }
            }else{
                $laVue = $viewContact->getView("Invalid");
                $myMeta = array('Title' => "Erreur...", 'Index' => 0);
            }
        } else { //Affichage
            $laVue = $viewContact->getView("Index");
            $metaClass = new modules_readMetas();
            $myMeta = $metaClass->readMeta(get_class($this), __FUNCTION__, "Contact us...");
        }
        return array('view'=>$laVue, 'meta'=>$myMeta);
    }
    
    private function validation(){
        if(!isset($_POST['Verif']) || $_POST['Verif'] != "ICI"){return false;}
        if($_POST['Anti'] != "rouge"){return false;}
        $dataToValidate = array(
          'Nom',
          'Message',
          'Email'
        );
        foreach ($dataToValidate as $value) {
            if(!isset($_POST[$value]) || strlen($_POST[$value])==0){
                return false;
            }
        }
        return true;
    }
}



?>