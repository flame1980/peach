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

    /*+
     * メソッド
     */
    private $method;

    /**
     * パラメータ
     */
    private $params;

    private $basePath;
    private $baseUrl;

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
        if ($this->method == 'POST') {
            $this->params = $_POST;
        } else {
            $this->params = $_GET;
        }
    }

    /**
     * 該当するキーのパラメータを取得
     */
    public function getParam($key, $default = null)
    {
        $ret = $default;
        if ($this->hasParam($key)) {
            $ret = $this->params[$key];
        }
        return $ret;
    }

    /**
     * パラメータを全て取得
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * パラメータをセット
     */
    public function setParam($key, $param)
    {
        $this->params[$key] = $param;
    }

    /**
     * パラメータをセット
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * パラメータの存在チェック
     */
    public function hasParam($key)
    {
        if (array_key_exists($key, $this->params)) {
            return true;
        }
        return false;
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
    public function getBaseUrl()
    {
        if ($this->baseUrl == null) {
            $schema = 'http';
            if ($this->isSsl()) {
                $schema = 'https';
            }
            $reqestPort = $_SERVER["SERVER_PORT"];
            $port = '';
            if (($schema == 'http' && $reqestPort != '80') || ($schema == 'https' && $reqestPort != '443')) {
                $port = ':' . $_SERVER["SERVER_PORT"];
            }
            $path = trim($this->getBasePath(), '/');
            $url = sprintf('%s://%s%s/%s', $schema, $_SERVER['SERVER_NAME'], $port, $path);
            $this->baseUrl = trim($url, '/');
        }
        return $this->baseUrl;
    }

    /**
     * relpathを取得する
     */
    public function getRelpath()
    {
        $relpath = '/';
        $basePath = $this->getBasePath();
        $tmp = explode('/', preg_replace('/^\//', '', $basePath));
        $depth = count($tmp);
        $relpath .= str_repeat('../', $depth);
        return $relpath;
    }
}
