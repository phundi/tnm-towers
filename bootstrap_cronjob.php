<?php
error_reporting(0);
ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);

@set_time_limit(0);
@clearstatcache();
date_default_timezone_set('UTC');
ini_set('date.timezone', 'UTC');
header("Connection: Keep-alive");
header('Content-Type: text/html; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
ini_set('memory_limit', -1);
$q = array();
$_DS       = DIRECTORY_SEPARATOR;
$_BASEPATH = realpath(dirname(__FILE__)) . $_DS;
require $_BASEPATH . 'config.php';
$_CONTROLLERS   = $_BASEPATH . 'controllers' . $_DS;
$_LIBS          = $_BASEPATH . 'libs' . $_DS;
$_REQUESTS      = $_BASEPATH . 'requests' . $_DS;
$_AJAX          = $_REQUESTS . 'ajax' . $_DS;
$_WORKER        = $_REQUESTS . 'worker' . $_DS;
$_ENDPOINT_PATH = $_BASEPATH . 'endpoint' . $_DS . $endpoint_v . $_DS;
$_UPLOAD        = $_BASEPATH . 'upload' . $_DS;
$_CACHE         = $_BASEPATH . 'cache' . $_DS;
//require_once $_LIBS . 'vendor' . $_DS . 'autoload.php';
require $_LIBS . 'db.php';
require $_LIBS . 'cache.php';
require $_LIBS . 'imagethumbnail.php';
require $_LIBS . 'theme.php';
require_once $_LIBS . 'webtopay.php';
require $_BASEPATH . 'core.php';
require $_LIBS . 'dataset.php';

$config->isDailyCredit = IsGotCredit();
if( ISSET( $_GET['theme'] ) && in_array($_GET['theme'], ['default', 'love'])){
    $_SESSION['theme'] = $_GET['theme'];
}

if( ISSET( $_SESSION['theme'] ) ){
    $config->theme = $_SESSION['theme'];
}

if( isset($_GET['language']) && $_GET['language'] !== '' && in_array($_GET['language'],array_keys(LangsNamesFromDB()))){
    setcookie("activeLang", Secure($_GET['language']), time() + (10 * 365 * 24 * 60 * 60), '/');
}

$config->filesVersion = '1.7';
