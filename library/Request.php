<?php

/**
 * リクエストクラス
 */
class Request
{

    /**
     * このクラスのインスタンス
     */
    private static $instance;

    /**
     * メソッド
     */
    private $method;

    private $postParams;
    private $getParams;

    private $basePath;

    /**
     * インスタンス取得
     *
     * @access public
     * @param none
     * @return object インスタンス
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->postParams = $_POST;
        $this->getParams = $_GET;
    }

    /**
     * $_GETの値を取得
     */
    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $this->getParams;
        } elseif (array_key_exists($key, $this->getParams)) {
            return $this->getParams[$key];
        } else {
            return $default;
        }
    }

    /**
     * $_POSTの値を取得
     */
    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $this->postParams;
        } elseif (array_key_exists($key, $this->postParams)) {
            return $this->postParams[$key];
        } else {
            return $default;
        }
    }

    /**
     * メソッドを取得
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * メソッド判定
     */
    public function isMethod($method)
    {
        if ($this->getMethod() == $method) {
            return true;
        }
        return false;
    }

    /**
     * POST判定
     */
    public function isPost()
    {
        if ($this->isMethod('POST')) {
            return true;
        }
        return false;
    }

    /**
     * GET判定
     */
    public function isGet()
    {
        if ($this->isMethod('GET')) {
            return true;
        }
        return false;
    }

    /**
     * SSL判定
     */
    public function isSsl()
    {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] !== 'off')) {
            return true;
        }
        return false;
    }

    /**
     * Set the relative path of the directory
     * where the index.php as seen from the document root
     *
     * @param string $path
     * @return void
     */
    public function setBasePath($path)
    {
        $this->basePath = $path;
    }

    /**
     * Get the relative path of the directory
     * where the index.php as seen from the document root
     *
     * @return string
     */
    public function getBasePath()
    {
        if ($this->basePath == null) {
            $this->basePath = preg_replace('|/[^/]*\.php$|', '', $_SERVER['SCRIPT_NAME']);
        }
        return $this->basePath;
    }

    /**
     * Get url of top page of site
     *
     * @return string
     */
    public function getBaseUrl($protocol = null)
    {
        if ($protocol == 'http') {
            $schema = 'http';
        } elseif ($protocol == 'https') {
            $schema = 'https';
        } else {
            $schema = 'http';
            if ($this->isSsl()) {
                $schema = 'https';
            }
        }

        $reqestPort = $_SERVER["SERVER_PORT"];
        $path = trim($this->getBasePath(), '/');
        $url = sprintf('%s://%s/', $schema, $_SERVER['SERVER_NAME']);
        // $this->baseUrl = trim($url, '/');

        return $url;
    }

    /**
     * relpathを取得する
     */
    public function getRelpath()
    {
        $relpath = '';
        $basePath = $this->getBasePath();
        $tmp = [];
        if (!empty($basePath)) {
            $tmp = explode('/', preg_replace('/^\//', '', $basePath));
        }

        $depth = count($tmp);
        $relpath .= str_repeat('../', $depth);
        return $relpath;
    }
}
