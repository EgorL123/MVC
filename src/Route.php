<?php

namespace Core;

class Route
{
    private $controllerName;

    private $actionName;

    private bool $processed = false;

    private $routes;


    /**
     * @throws RouteException
     */
    private function process(): void
    {
        if (!$this->processed) {
            $path = parse_url($_SERVER['REQUEST_URI'])['path'];

            if (($route = $this->routes[$path] ?? null) !== null) {
                $this->controllerName = $route[0];
                $this->actionName = $route[1];
            } else {
                $parts = explode('/', $path);

                if (!empty($parts[2])) {
                    $this->controllerName = '\\App\\Controller\\' . ucfirst(strtolower($parts[2]));
                } else {
                    $this->controllerName = \App\Controller\User::class;
                }

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


    /**
     * @param $path
     * @param $controllerName
     * @param $actionName
     */
    public function addRoute($path, $controllerName, $actionName): void
    {
        $this->routes["/public_html/" . $path] = [
            $controllerName,
            $actionName
        ];
    }

    /**
     * @throws RouteException
     */
    public function getControllerName(): string
    {
        if (!$this->processed) {
            $this->process();
        }

        return $this->controllerName;
    }

    /**
     * @throws RouteException
     */
    public function getActionName(): string
    {
        if (!$this->processed) {
            $this->process();
        }

        return $this->actionName . 'Action';
    }
}
