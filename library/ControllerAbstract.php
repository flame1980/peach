<?php

require_once('Request.php');
require_once('Session.php');
require_once('View.php');

/**
 * ControllerAbstract
 */
abstract class ControllerAbstract
{

    public $cssPreLoad = [];
    public $cssPostLoad = [];
    public $jsPreLoad = [];
    public $jsPostLoad = [];

    protected $request;
    protected $session;

    protected $view;
    protected $viewPage;

    protected $errorList;

    /**
     * 主処理
     */
    public function exec()
    {

        // 全体の前処理
        $this->initialize();

        // コントローラ実行の前処理
        $this->setUp();

        // コントローラ実行
        $this->execute();

        // コントローラ実行の後処理
        $this->tearDown();

        // コントローラの各処理が終わったらVIEWに値をセットする
        $this->view->set('relpath', $this->request->getRelpath());
        $this->view->set('baseurl', $this->request->getBaseUrl());
        $this->view->set('baseurlhttp', $this->request->getBaseUrl('http'));
        $this->view->set('baseurlhttps', $this->request->getBaseUrl('https'));
        $this->view->set('cssPreLoad', $this->cssPreLoad);
        $this->view->set('cssPostLoad', $this->cssPostLoad);
        $this->view->set('jsPreLoad', $this->jsPreLoad);
        $this->view->set('jsPostLoad', $this->jsPostLoad);

        // 描画処理
        $this->view->render($this->viewPage);

        // 全体の後処理
        $this->finalize();
    }

    /**
     * アクションをセットする
     */
    public function setAction($action, $path = null)
    {
        $viewPage = strtolower($action);
        if (!empty($path)) {
            $viewPage = $path.DS.$viewPage;
        }
        $this->viewPage = $viewPage;
    }

    /**
     * redirect
     */
    public function redirect($path, $responseCode = 302)
    {
        $url = $path;
        if (!preg_match('/^(http|https)/', $path)) {
            $url = $this->request->getBaseUrl() . '/' . ltrim($path, '/');
        }
        header("Location: {$url}", true, $responseCode);
        exit;
    }

    /**
     * 全体の前処理
     *
     * @access protected
     */
    protected function initialize()
    {
        $this->request = Request::getInstance();
        $this->session = Session::getInstance();
        $this->view = new View();
    }

    /**
     * 全体の後処理
     *
     * @access protected
     */
    protected function finalize()
    {

    }

    /**
     * コントローラ実行の前処理
     */
    protected function setUp(){}

    /**
     * コントローラ実行の後処理
     */
    protected function tearDown(){}

    /**
     * コントローラ実行
     */
    abstract protected function execute();

    protected function addError($name, $message)
    {
        $this->errorList[$name][] = $message;
    }

}