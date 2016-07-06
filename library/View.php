<?php

require_once('Smarty.class.php');

class View
{

    const EXTENSION = '.html';

    /**
     * Smarty object
     * @var Smarty
     */
    protected $smarty;

    /**
     * コンストラクタ
     *
     * @param string $tmplPath
     * @param array $extraParams
     * @return void
     */
    public function __construct($extraParams = [])
    {
        $this->smarty = new Smarty;

        $this->smarty->template_dir    = TEMPLATE_DIR.DS;
        $this->smarty->compile_dir     = TMP_DIR.DS.'templates_c'.DS;
        $this->smarty->config_dir      = TMP_DIR.DS.'configs'.DS;
        $this->smarty->cache_dir       = TMP_DIR.DS.'cache'.DS;
        $this->smarty->left_delimiter  = "<!--{";
        $this->smarty->right_delimiter = "}-->";

        foreach ($extraParams as $key => $value) {
            $this->smarty->$key = $value;
        }
    }

    /**
     * テンプレートエンジンオブジェクトを返します
     *
     * @return Smarty
     */
    public function getEngine()
    {
        return $this->smarty;
    }

    /**
     * テンプレートへのパスを設定します
     *
     * @param string $path パスとして設定するディレクトリ
     * @return void
     */
    public function setScriptPath($path)
    {
        if (is_readable($path)) {
            $this->smarty->template_dir = $path;
            return;
        }
        throw new Exception('無効なパスが指定されました');
    }

    /**
     * 変数をテンプレートに代入します
     *
     * @access public
     * @param mixed $key キー
     * @param mixed $val 値
     * @return void
     */
    public function set($key, $val = null)
    {
        if (is_array($key)) {
            $this->smarty->assign($key);
            return;
        }

        $this->smarty->assign($key, $val);
    }

    /**
     * clear_assign
     */
    public function clearAssign($key)
    {
        $this->smarty->clear_assign($key);
    }

    /**
     * clear_all_assign
     */
    public function clearAllAssign()
    {
        $this->smarty->clear_all_assign();
    }

    /**
     * テンプレートを処理し、結果を出力します
     *
     * @param string $name 処理するテンプレート
     * @return string 出力結果
     */
    public function render($name)
    {
        $name .= self::EXTENSION;
        $result = false;
        try {
            $result = $this->smarty->display($name);
        } catch (Exception $e) {
            // VIEWファイルが存在しなかったらひとまず404にする
            // TODO: ログ出力とか（headerクラスとかのほうが良いか。。）
            header("HTTP/1.0 404 Not Found");
            $result = $this->smarty->display('error/404.html');
        }
        return $result;
    }

}
