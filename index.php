<?php
$APP_ENV = parse_ini_file("env.ini", true);

define('VERSION', $APP_ENV['app']['version']);
define('DEV_MODE',$APP_ENV['app']['mode'] == 'prod' ? 0 : 1);

require_once('config/common.php');
require_once('config/'.$APP_ENV['app']['mode'].'/app.php');
require_once('config/'.$APP_ENV['app']['mode'].'/db.php');

//ini_set('date.timezone','Asia/Shanghai');
if(isset($APP_ENV['session']) && isset($APP_ENV['session']['save_handler'])) {
    ini_set('session.save_handler', $APP_ENV['session']['save_handler']);
    ini_set('session.save_path', $APP_ENV['session']['save_path']);
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');
require_once(DIR_SYSTEM . 'library/mdb.php');
// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: application/json; charset=utf-8');
$registry->set('response', $response);

//Mdb
$mdb = new Mdb();
$registry->set('mdb',$mdb);

$cache = $mdb->getRedis();
$registry->set('cache', $cache);

$scache = $mdb->getRedis();
$registry->set('redis',$scache);

// DB
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

function error_handler($errno, $errstr, $errfile, $errline) {
    global $config;

    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }

    if (DEV_MODE) {
        echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
    }

    return true;
}

// Error Handler
set_error_handler('error_handler');

// Front Controller
$controller = new Front($registry);

//$controller->addPreAction(new Action('common/permission'));

// Router
if (isset($request->get['route'])) {
    $action = new Action($request->get['route']);
} else {
    echo '{"status":0,"error":"param error"}';
    exit;
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));
// Output
$response->output();

//$klog->addVisitLog();

?>