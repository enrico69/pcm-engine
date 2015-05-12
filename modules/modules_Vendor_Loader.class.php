<?php

class modules_Vendor_Loader {

    public function includeClass($className) {

        require_once 'vendor/vendor.php';

        $path = "";

        foreach ($vendorModules as $module => $directPath) {

            if ($module == $className || $module == strtolower($className)) {

                $path = 'vendor/' . $directPath . '/' . $className . '.php';
            }
        }

        if (file_exists($path)) {
            include $path;
        } else { 
            return false;
        }
        
    }

}
