<?php

class views_Contact extends engine_Views_Abstract {

    protected function Index() {
        include ('views/templates/contactForm/viewFormContact.php');
    }
    
    protected function Success() {
        include ('views/templates/contactForm/viewFormContactSuccess.php');
    }
    
    protected function Error() {
        include ('views/templates/contactForm/viewFormContactError.php');
    }
    
    protected function Invalid() {
        include ('views/templates/contactForm/viewFormContactInvalid.php');
    }

}

?>