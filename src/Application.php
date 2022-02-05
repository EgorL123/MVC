<?php

namespace Core;

use App\Controller\User;

class Application
{
    private \Core\Route $route;
    private $controller;
    private $action;

    public function __construct()
    {
        $this->route = new Route();
    }

    public function run(): void
    {

        try {
            session_start();
            $this->addRoutes();
            $this->initController();
            //$this->initUser();
            $action = $this->action;
            $this->controller->setView(new Twig());
            $this->controller->$action();
        } catch (RedirectException $e) {
            header("Location:" . $e->getUrl());
        } catch (RouteException $e) {
            header('HTTP/1.0 404 Not Found');
        }
    }

    public function initController(): void
    {
        $controllerName = $this->route->getControllerName();
        $this->controller = new $controllerName();
        $this->action = $this->route->getActionName();
    }

    public function addRoutes(): void
    {

        /** @uses \App\Controller\User::registerAction() */
        $this->route->addRoute('user/register', User::class, 'register');
/** @uses \App\Controller\User::profileAction() */
        $this->route->addRoute('user/profile', User::class, 'profile');
/** @uses \App\Controller\User::authorizationAction() */
        $this->route->addRoute('user/authorization', User::class, 'authorization');
/** @uses \App\Controller\User::indexAction() */
        $this->route->addRoute('user/index', User::class, 'index');
/** @uses \App\Controller\User::baseAction() */
        $this->route->addRoute('', User::class, 'index');
/** @uses \App\Controller\Blog::indexAction() */
        $this->route->addRoute('blog', \App\Controller\Blog::class, 'index');
/** @uses \App\Controller\Blog::getAllAction() */
        $this->route->addRoute('blog/getAll', \App\Controller\Blog::class, 'getAll');
/** @uses \App\Controller\Blog::sendAction() */
        $this->route->addRoute('blog/send', \App\Controller\Blog::class, 'send');
/** @uses \App\Controller\Blog::indexAction() */
        $this->route->addRoute('blog/delete', \App\Controller\Blog::class, 'delete');
    }

    public function initUser(): void
    {
        $id = $_SESSION['id'] ?? null;
        if ($id) {
            $user = \App\Model\User::get($id);
            if ($user !== null) {
                $this->controller->setUser($user);
            }
        }
    }
}
