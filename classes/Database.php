<?php

class Database {

    private static $_instance = null;
    private $_pdo;
    private $_query;
    private $_results;
    private $_error = false;
    private $_error_message;
    private $_error_messages_log = array();
    private $_count = 0;

    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new Database();
        }
        return self::$_instance;
    }

    /**
     * pepare and execute queries
     * @param type $sql
     * @param type $params
     * @return \Database
     */
    public function query($sql, $params = array()) {
        $this->_error = false;
        $this->_error_message = '';
        if ($this->_query = $this->_pdo->prepare($sql)) {
            if (count($params)) {
                //check we are using numeric keys to bind values
                if (isset($params[0])) {
                    $position = 1;
                }
                foreach ($params as $key => $value) {
                    if (isset($position)) {
                        $this->_query->bindValue($position, $value);
                        $position++;
                    } else {
                        $this->_query->bindValue($key, $value);
                    }
                }
            }

            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $error = $this->_query->errorInfo();
                if (isset($error[2])) {
                    $this->_error_message = $error[2];
                    $this->_error_messages_log[] = $error[2];
                }
                $this->_error = true;
            }
        }
        return $this;
    }

    /**
     * check for an error in the last sql query execution
     * @return boolean
     */
    public function error() {
        return $this->_error;
    }

    public function getErrorMessage() {
        return $this->_error_message;
    }

    public function getErrorMessagesLog() {
        return $this->_error_messages_log;
    }

    public function action($action, $table, $where) {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }

    public function count() {
        return $this->_count;
    }

    public function getResults() {
        return $this->_results;
    }

    public function getFirstResult() {
        $res = $this->getResults();
        return $res[0];
    }

    public function insert($table, $fields = array()) {
        if (count($fields)) {
            $keys = array_keys($fields); //get fields represent column in table
            $values_placeholder = array();
            foreach ($keys as $k => $v) {
                $values_placeholder[$k] = ':' . $v;
            }
            $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES (" . implode(',', $values_placeholder) . ")";
            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

    public function update($table, $ID, $fields = array()) {
        if (count($fields)) {
            $keys = array_keys($fields); //get fields represent column in table
            $values_placeholder = array();
            foreach ($fields as $k => $v) {
                $query_elements[] = "`{$k}`=:" . $k;
                $values_placeholder[':' . $k] = $v;
            }
            $sql = "UPDATE  {$table} SET " . implode(',', $query_elements) . " WHERE ID={$ID}";
            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

}
