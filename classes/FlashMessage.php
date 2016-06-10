<?php

/**
 * This class is used to manage messages in the session
 */
class FlashMessage {

    /**
     * default message type to use while saving a message and retrieving a message
     */
    const DEFAULT_MESSAGE_TYPE = 'information';

    /**
     * Save a message in session messages array
     * @param string $message
     * @param string $type
     */
    public static function pushFlashMessage($message, $type = self::DEFAULT_MESSAGE_TYPE) {
        Session::push($type, $message, (Config::get('flash/messages_key')));
    }

    /**
     * Check  messages exists  in session for a message type or not
     * @param string $type
     * @return boolean
     */
    public static function hasTypeMessages($type = self::DEFAULT_MESSAGE_TYPE) {
        $message_key = (Config::get('flash/messages_key'));
        if (isset($_SESSION[$message_key][$type][0]) && !empty($_SESSION[$message_key][$type][0])) {
            return true;
        }
        return false;
    }

    /**
     * Checks any messages exists in session 
     * @return boolean
     */
    public static function hasAnyMessages() {
        $message_key = (Config::get('flash/messages_key'));
        if (isset($_SESSION[$message_key]) && !empty($_SESSION[$message_key]) && count($_SESSION[$message_key])) {
            foreach ($_SESSION[$message_key] as $type => $messages) {
                if (count($messages)) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Display messages for a message type
     * @param string $type
     * @param string $element_class
     * @param string $element_wrapper
     */
    public static function displayFlashMessages($type = self::DEFAULT_MESSAGE_TYPE, $element_class = '', $element_wrapper = 'div') {
        $message_key = (Config::get('flash/messages_key'));
        $element_class = (empty($element_class) ? self::getMessagesClass($type) : $element_class);
        if (isset($_SESSION[$message_key][$type][0]) && !empty($_SESSION[$message_key][$type][0])) {
            foreach ($_SESSION[$message_key][$type] as $index => $message) {
                echo '<' . $element_wrapper . ' class=' . $element_class . '>' . $message . '</' . $element_wrapper . '>';
                unset($_SESSION[$message_key][$type][$index]);
//                TODO: remove above code and put in Session class
            }
        }
    }

    /**
     * Display all messages exists in the session
     */
    public static function displayAllFlashMessages() {
        $message_key = (Config::get('flash/messages_key'));
        if (isset($_SESSION[$message_key]) && !empty($_SESSION[$message_key]) && count($_SESSION[$message_key])) {
            foreach ($_SESSION[$message_key] as $type => $messages) {
                self::displayFlashMessages($type);
            }
        }
    }

    /**
     *  Retrieve message type class from the config array
     * @param string $type
     * @return string
     */
    public static function getMessagesClass($type = self::DEFAULT_MESSAGE_TYPE) {
        return Config::get('flash/messages_class/' . $type);
    }

}
