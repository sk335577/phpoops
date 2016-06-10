<?php

class Input {

    public static function exists($type = 'post') {

        if ($type == 'post') {
            return ((!empty($_POST)) ? true : false);
        } elseif ($type == 'get') {
            return ((!empty($_GET)) ? true : false);
        } else {
            return false;
        }
    }

    public static function get($item) {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } elseif (isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }

}
