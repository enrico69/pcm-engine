<?php

class views_Books extends engine_Views_Abstract {

    protected function Index() {
        include ('views/templates/viewIndexBooks.php');
    }

    protected function Add() {
        include ('views/templates/viewFormBooks.php');
    }

    protected function None() {
        include ('views/templates/viewNoBooks.php');
    }

    protected function Success() {
        include ('views/templates/viewSuccessBooks.php');
    }

}

?>
