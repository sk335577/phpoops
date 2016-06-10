<?php

class Config {

    public static function get($path) {
        if (!empty($path)) {
            $config = $GLOBALS['config'];

            foreach (explode('/', $path) as $item) {
                if (isset($config[$item])) {
                    $config = $config[$item];
                }
            }
            return $config;
        }
        return false;
    }

}
