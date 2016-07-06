<?php

require_once('Request.php');

class Dispatcher
{

    const DEFAULT_SCRIPT_NAME = '/index.php';

    private $action;
    private $path;

    public function __construct()
    {
        $page = Request::getInstance()->getParam('_page_');
        if (empty($page)) {
            $page = '/index.html';
        }

        // URLが.htmlで終わらなかったらひとまず404にする
        // TODO: ログ出力とか（headerクラスとかのほうが良いか。。）
        if (!preg_match('/(.)*.html$/', $page)) {
            header("HTTP/1.0 404 Not Found");
            $page = '/error/404.html';
        }

        $pageParts = explode('/', $page);

        $path = [];
        foreach ($pageParts as $val) {
            if ($val === reset($pageParts)) {
                continue;   // 最初の1つは捨てる
            }
            if ($val === end($pageParts)) {
                $action = preg_replace('/\.(.)*$/', '', $val);
            } else {
                $path[] = $val;
            }
        }

        $this->action = ucfirst(strtolower($action));
        $this->path = implode('/', $path);
        Request::getInstance()->setBasePath(DS.$this->path);
    }

    public function dispatch()
    {
        $filePath = CONTROLLER_DIR.DS.$this->path.DS.$this->action.'.php';
        if (file_exists($filePath)) {
            require_once($filePath);
            $controller = new $this->action();
        } else {
            require_once('ControllerStandard.php');
            $controller = new ControllerStandard();
        }

        $controller->setAction($this->action, $this->path);
        $controller->exec();

    }
}