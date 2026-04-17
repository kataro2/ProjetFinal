<?php
session_start();

// Autoload de classes
spl_autoload_register(function($class_name) {
    $paths = [
        'models/',
        'controllers/'
    ];
    
    foreach($paths as $path) {
        $file = $path . $class_name . '.php';
        if(file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

// Router simples
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

$controllerName = ucfirst($controller) . 'Controller';

if(class_exists($controllerName)) {
    $controllerObj = new $controllerName();
    
    if(method_exists($controllerObj, $action)) {
        $controllerObj->$action();
    } else {
        die("Método não encontrado!");
    }
} else {
    die("Controlador não encontrado!");
}
?>