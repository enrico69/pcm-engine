<?php

class modules_Email {

    public function sendEmail($TO, $From, $Message, $Sujet, $h = "") {
        if (is_array($TO)) {
            $tempTO = "";
            foreach ($TO as $value) {
                $tempTO = $value . ";";
            }
            $TO = $tempTO;
        }

        if ($h == "") {
            $h = "From: " . $From;
        }
        if (mail($TO, $Sujet, $Message, $h)) {
            return true;
        } else {
            return false;
        }
    }

}
