<?php

class Session
{

    /**
     * このクラスのインスタンス
     */
    private static $instance;

    public function __construct()
    {
        session_start();
    }

    /**
     * インスタンス取得
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getParam($key, $default = null)
    {
        $ret = $default;
        if (array_key_exists($key, $_SESSION)) {
            $ret = $_SESSION[$key];
        }
        return $ret;
    }

    public function setParam($key, $data)
    {
        $_SESSION[$key] = $data;
    }

    public function removeParam($key)
    {
        unset($_SESSION[$key]);
    }

    public function hasParam($key)
    {
        if (array_key_exists($key, $_SESSION)) {
            return true;
        }
        return false;
    }

    public function clear()
    {
        session_destroy();
    }

}