<?php 

declare(strict_types = 1);
// Routing Logic

namespace App\Core;

class Router {
    protected $request;
    protected $routes = [];

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function get($path, $controllerMethod){
       
        $this->routes['GET'][$path] = $controllerMethod;
    }

    public function post($path, $controllerMethod){
       
        $this->routes['POST'][$path] = $controllerMethod;
    }

    public function resolve(){
        $method = $this->request->getMethod();
        $path = $this->request->getPath();
        

        $controllerMethod = $this->routes[$method][$path] ?? null;

        if(!$controllerMethod){
            
            return;
        }

        [$controller, $method] = explode('@', $controllerMethod);
        if(class_exists($controller)){
            
            $controllerInstance = new $controller;
            if(method_exists($controllerInstance, $method)){
                call_user_func_array([
                    $controllerInstance,
                    $method
                ], [$this->request]);
            } else {
                echo "Method $method not found in $controller";
            }
        } else {
            echo "Controller class $controller not found";
        }
    }
}