<?php

class Hash {

    public static function createHash($string, $salt = '') {
        return hash('sha256', $string . $salt);
    }

    public static function createSalt($length) {
        return mcrypt_create_iv($length);
    }

    public static function unique() {
        return self::createHash(uniqid());
    }

}
