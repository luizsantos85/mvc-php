<?php

namespace Core;

use Config\app as Config;

class RouterBase
{

    /**
     * Run the router
     *
     * @param array $routes
     * @return void
     */
    public function run($routes): void
    {
        $method = Request::getMethod();
        $url = Request::getUrl();

        // Default items
        $controller = Config::ERROR_CONTROLLER;
        $action = Config::DEFAULT_ACTION;
        $args = [];
        
        if (isset($routes[$method])) {
            foreach ($routes[$method] as $route => $callback) {
                // Identifica os argumentos e substitui por regex
                $pattern = preg_replace('(\{[a-z0-9]{1,}\})', '([a-z0-9-]{1,})', $route);
                
                // Faz o match da URL
                if (preg_match('#^(' . $pattern . ')*$#i', $url, $matches) === 1) {
                    array_shift($matches);
                    array_shift($matches);
                    
                    // Pega todos os argumentos para associar
                    $itens = array();
                    if (preg_match_all('(\{[a-z0-9]{1,}\})', $route, $m)) {
                        $itens = preg_replace('(\{|\})', '', $m[0]);
                    }

                    // Faz a associação
                    $args = array();
                    foreach ($matches as $key => $match) {
                        $args[$itens[$key]] = $match;
                    }

                    // Seta o controller/action
                    $callbackSplit = explode('@', $callback);
                    $controller = $callbackSplit[0];
                    if (isset($callbackSplit[1])) {
                        $action = $callbackSplit[1];
                    }

                    break;
                }
            }
        }

        $controller = 'App\\Controllers\\' . $controller;
        // $defineController = new $controller();
        // $defineController->$action($args);
        
        $controllerInstance = $this->resolveController($controller);
        $controllerInstance->$action($args);
    }

    /**
     * Resolve o controller se tiver construtor
     *
     * @param string $controllerClass
     * @return object
     */
    private function resolveController($controllerClass)
    {
        $reflection = new \ReflectionClass($controllerClass);

        // Verifica se a classe tem construtor
        if(!$reflection->hasMethod('__construct')) {
            return new $controllerClass();
        }

        // Pega os parâmetros do construtor
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();

        // Resolve os parametros
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            if ($type instanceof \ReflectionNamedType) {
                $typeName = $type->getName();
                if(class_exists($typeName)){
                    $dependencies[] = new $typeName();
                }
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
