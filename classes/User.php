<?php

class User {

    private $_db;
    public $_is_logged_in;
    public $_data;
    private $_session_name;
    private $_cookie_name;

    function __construct($user = null) {
        $this->_db = Database::getInstance();
        $this->_session_name = Config::get('session/session_name');
        $this->_cookie_name = Config::get('remember/cookie_name');
        if (!$user) {
            if (Session::exists($this->_session_name)) {
                $user = Session::get($this->_session_name);

                if ($this->find($user)) {
                    $this->_is_logged_in = true;
                } else {
                    //logout
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function createUser($fields = array()) {
        return ($this->_db->insert(Config::get('mysql/table_prefix') . 'users', $fields));
    }

    public function update($fields = array(), $id = null) {
        if (!$id && $this->isUserLoggedIn()) {
            $id = $this->data()->ID;
        }
        return ($this->_db->update(Config::get('mysql/table_prefix') . 'users', $id, $fields));
    }

    public function find($user = null, $field = 'ID') {
        if ($user) {
            $data = $this->_db->get(Config::get('mysql/table_prefix') . 'users', array($field, '=', $user));
            if ($data->count()) {
                $this->_data = $data->getFirstResult();
                return true;
            }
        }
    }

    public function login($username = null, $password = null, $remember = false) {
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_session_name, $this->data()->ID);
        } else {
            $user = $this->find($username, 'username');
            if ($user) {
                if ($this->data()->password === Hash::createHash($password, $this->data()->salt)) {
                    Session::put($this->_session_name, $this->data()->ID);
                    if ($remember) {
                        $hash = Hash::unique();
                        $hash_check = $this->_db->get(Config::get('mysql/table_prefix') . 'users_session', array('userID', '=', $this->data()->ID));
                        if (!$hash_check->count()) {
                            $this->_db->insert(Config::get('mysql/table_prefix') . 'users_session', array(
                                'userID' => $this->data()->ID,
                                'hash' => $hash
                            ));
                        } else {
                            $hash = $hash_check->getFirstResult()->hash;
                        }
                        Cookie::put($this->_cookie_name, $hash, (Config::get('remember/cookie_expiry')));
                    }
                    return true;
                }
            }
        }
    }

    public function data() {
        return $this->_data;
    }

    public function exists() {
        return (!empty($this->_data) ? true : false);
    }

    public function isUserLoggedIn() {
        return $this->_is_logged_in;
    }

    public function logout($redirect_url = 'login.php') {
        if ($this->isUserLoggedIn()) {
            $this->_db->delete(Config::get('mysql/table_prefix') . 'users_session', array('userID', '=', $this->data()->ID));
            Session::delete($this->_session_name);
            Cookie::delete($this->_cookie_name);
            Redirect::to($redirect_url);
        } else {
            Redirect::to('login.php');
        }
    }

}
