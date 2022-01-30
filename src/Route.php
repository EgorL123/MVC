<?php

namespace Core;

class Route
{
    private $controllerName;
    private $actionName;
    private bool $processed = false;
    private $routes;


    private function process(): void
    {
        if (!$this->processed) {
            $path = parse_url($_SERVER['REQUEST_URI'])['path'];

            if (($route = $this->routes[$path] ?? null) !== null) {
                $this->controllerName = $route[0];
                $this->actionName = $route[1];
            } else {
                $parts = explode('/', $path);
                $this->controllerName = '\\App\\Controller\\' . ucfirst(strtolower($parts[2]));
                $this->actionName = 'index';


                if (!class_exists($this->controllerName)) {
                    throw new RouteException('Class not exist');
                }


                if (!method_exists($this->controllerName, $this->actionName . "Action")) {
                    throw new RouteException($this->actionName . "Action" . " not exist in" . $this->controllerName);
                }
            }
        }
    }


    public function addRoute($path, $controllerName, $actionName): void
    {
        $this->routes["/html/" . $path] = [
            $controllerName,
            $actionName
        ];
    }

    public function getControllerName(): string
    {
        if (!$this->processed) {
            $this->process();
        }

        return $this->controllerName;
    }

    public function getActionName(): string
    {
        if (!$this->processed) {
            $this->process();
        }

        return $this->actionName . 'Action';
    }
}
