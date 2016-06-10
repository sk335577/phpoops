<?php

session_start();
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'phpoops',
        'table_prefix' => 'phpoops_'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
    'flash' => array(
        'messages_key' => 'msg',
        'messages_class' => array(
            'information' => '.info',
            'success' => '.success'
        )
    ),
    'setting' => array(
        'dashboard_directory' => 'dashboard',
    ),
    'password' => array(
        'min' => 6,
        'max' => 20,
    ),
    'site_url' => array(
        'website_url' => 'phpoops.local',
    ),
    'site_path' => array(
        'abspath' => dirname(__FILE__),
        'dashboard_name' => 'dashboard',
        'dashboard_url'
    )
);

spl_autoload_register(function($class) {
    require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hash_check = Database::getInstance()->get(Config::get('mysql/table_prefix') . 'users_session', array('hash', '=', $hash));
    if ($hash_check->count()) {
        $user = new User($hash_check->getFirstResult()->userID);
        $user->login();
    }
}    