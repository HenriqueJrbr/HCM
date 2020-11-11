<?php
session_save_path('tmp');
session_start();
require 'vendor/autoload.php';
require 'config.php';

    ini_set('display_errors',1);
    ini_set('display_startup_erros',1);
    error_reporting(E_ALL);
    
spl_autoload_register(function ($class){
    if(file_exists('controllers/'.$class.'.php')) {
            require_once 'controllers/'.$class.'.php';
    } elseif(file_exists('models/'.$class.'.php')) {
            require_once 'models/'.$class.'.php';
    } elseif(file_exists('core/'.$class.'.php')) {
            require_once 'core/'.$class.'.php';
    }elseif(file_exists('regrasform/'.$class.'.php')) {
            require_once 'regrasform/'.$class.'.php';
    }elseif(file_exists('helper/'.$class.'.php')) {
        require_once 'helper/' . $class . '.php';
    }
});

$core = new Core();
$core->run();
?>
