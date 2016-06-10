<?php

class Session {

    /**
     * Check session key exists
     * @param string $name
     * @return boolean
     */
    public static function exists($name) {
        return (isset($_SESSION[$name]) ? true : false);
    }

    /**
     * Put a key and value in session
     * @param string $name
     * @param string $value
     * @return string
     */
    public static function put($name, $value) {
        return $_SESSION[$name] = $value;
    }

    /**
     * push a value in session array
     * @param string $key
     * @param string $value
     * @param null|string $section
     */
    public static function push($key, $value, $section = null) {
//        TODO: find away to push data in session  we will send like section/key
        if (is_null($section)) {
            $_SESSION[$key][] = $value;
        } else {
            $_SESSION[$section][$key][] = $value;
        }
    }

    /**
     * get a value from session
     * @param string $name
     * @return string
     */
    public static function get($name) {
        return $_SESSION[$name];
    }

    /**
     * Remove a value from session
     * @param string $name
     */
    public static function delete($name) {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function flash($name, $string = '') {
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
    }

}
