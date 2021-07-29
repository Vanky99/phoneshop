<?php
class Router {
    public $request = [];
    private $routers = [];
    
    function get(string $action, $controller, $method) {
        $request = $this->request;
        $this->routers["$action.$method"] = (function() use ($controller, $method, $request) {
            $controller = new $controller();
            return $controller->{$method}($request);
        });
    }

    function dispatch($action, $method)
    {
        $callback = $this->routers["$action.$method"];
        echo call_user_func($callback);
    }
}
session_start();
$router = new Router();
$router->request = [
    'GET' => $_GET,
    'POST' => $_POST,
    'SESSION' => $_SESSION,
];

include_once 'controllers/PhoneController.php';
include_once 'controllers/PostController.php';
include_once 'controllers/CommentController.php';
include_once 'controllers/CartController.php';
include_once 'controllers/LoginController.php';

$router->get('phone', PhoneController::class, 'index');
$router->get('phone', PhoneController::class, 'show');
$router->get('phone', PhoneController::class, 'store');
$router->get('phone', PhoneController::class, 'update');
$router->get('phone', PhoneController::class, 'destroy');
$router->get('phone', PhoneController::class, 'add_post');
$router->get('post', PostController::class, 'index');
$router->get('post', PostController::class, 'add_comment');
$router->get('post', PostController::class, 'censore');
$router->get('post', PostController::class, 'destroy');
$router->get('comment', CommentController::class, 'censore');
$router->get('comment', CommentController::class, 'destroy');
$router->get('cart', CartController::class, 'index');
$router->get('cart', CartController::class, 'view_orders');
$router->get('cart', CartController::class, 'view_order');
$router->get('cart', CartController::class, 'verify_order');
$router->get('cart', CartController::class, 'buy');
$router->get('cart', CartController::class, 'remove');
$router->get('cart', CartController::class, 'submit');
$router->get('login', LoginController::class, 'index');
$router->get('login', LoginController::class, 'login');
$router->get('login', LoginController::class, 'logout');

//Execute action
$action = isset($_GET['action']) ? $_GET['action'] : 'phone';
$method = isset($_GET['method']) ? $_GET['method'] : 'index';
$router->dispatch($action, $method);