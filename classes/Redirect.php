<?php

class Redirect {

    public static function to($location = null) {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header("HTTP/1.0 404 Not Found");
                        require_once '404.php';
                        die();
                        break;

                    default:
                        break;
                }
            }
            header('location:' . $location);
            die();
        }
    }

}
