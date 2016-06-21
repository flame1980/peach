<?php
/**
 * config.php
 * 初期設定ファイル
 */

// エンコード指定
// mb_internal_encoding('UTF-8');

// エラー表示指定
// error_reporting(0); // 本番時
// error_reporting (E_ALL);     // 全て表示
error_reporting(E_ALL & ~E_NOTICE); // 開発時
// ini_set('display_errors', 'Off');    // 本番時
ini_set('display_errors', 'On');

// DIRECTORY_SEPARATORのショートハンド指定
if (!defined('DS')) {
    define ('DS', DIRECTORY_SEPARATOR);
}

// projectのベースパス
if (!defined('BASE_DIR')) {
    define('BASE_DIR', dirname(__FILE__));
}

define('CONTROLLER_DIR', BASE_DIR.DS.'controller');
define('LIBRARY_DIR', BASE_DIR.DS.'library');
define('TEMPLATE_DIR', BASE_DIR.DS.'template');
define('VENDOR_DIR', BASE_DIR.DS.'vendor');
define('TMP_DIR', BASE_DIR.DS.'tmp');

// include_path追加設定
$includePath = array();
$includePath[] = LIBRARY_DIR;
$includePath[] = VENDOR_DIR.'/smarty/libs';

// include_path追加
foreach ($includePath as $path) {
    set_include_path(get_include_path().PATH_SEPARATOR.realpath(str_replace('\\', '/', $path)));
}
