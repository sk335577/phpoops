<?php

class Validation {

    private $_passed = false;
    private $_errors = array();
    private $_db = null;

    public function __construct() {
        $this->_db = Database::getInstance();
    }

    public function check($source, $items = array()) {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                $value = $source[$item];
                if ($rule == 'required' && empty($value)) {
                    $this->addError((isset($rules['name']) ? $rules['name'] : $item) . ' is required.');
                } elseif (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError((isset($rules['name']) ? $rules['name'] : $item) . " should contain {$rule_value} or more characters.");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError((isset($rules['name']) ? $rules['name'] : $item) . " should not contain more than {$rule_value} characters.");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError((isset($rules['name']) ? $rules['name'] : $item) . " should match with {$rule_value} .");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get('phpoops_users', array('username', '=', $value));
                            if ($check->count()) {
                                $this->addError((isset($rules['name']) ? $rules['name'] : $item) . " already exists.");
                            }
                            break;
                        case 'email':
                            $p = '/^[a-zA-Z0-9_]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/';
                            if (preg_match($p, $value) != 1) {
                                $this->addError((isset($rules['name']) ? $rules['name'] : $item) . " not valid.");
                            }
                            break;

                        default:
                            break;
                    }
                }
            }
        }
        if (empty($this->errors())) {
            $this->_passed = true;
        }
        return $this;
    }

    private function addError($error) {
        $this->_errors[] = $error;
    }

    public function errors() {
        return $this->_errors;
    }

    public function passed() {
        return $this->_passed;
    }

}
