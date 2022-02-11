<?php

namespace Core;

use App\Controller\Blog\BlogAdmin;
use App\Controller\Blog\BlogDelete;
use App\Controller\Blog\BlogGet;
use App\Controller\Blog\BlogIndex;
use App\Controller\Blog\BlogSend;
use App\Controller\User\UserAuth;
use App\Controller\User\UserChange;
use App\Controller\User\UserDelete;
use App\Controller\User\UserIndex;
use App\Controller\User\UserRegister;
use Illuminate\Database\Capsule\Manager as Capsule;

class Application
{
    private \Core\Route $route;

    private $controller;

    private $action;

    protected $container;

    public function __construct()
    {
        $this->route = new Route();
    }

    public function run(
        IPost $post,
        ?string $userId,
        IView $view,
        array $patternNames,
        $container
    ): void {

        try {
            $post->normalizeData();
            $this->addRoutes();
            $this->initController();
            $this->eloquentInit();
            $action = $this->action;
            $this->controller->setView($view);
            $this->controller->setContainer($container);

            echo $this->controller->$action($post, $userId, $patternNames);
        } catch (RedirectException $redirectException) {
            header("Location:" . $redirectException->getUrl());
        } catch (RouteException $routeException) {
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
        $this->route->addRoute('user/register', UserRegister::class, 'register');
        /** @uses \App\Controller\User::authorizationAction() */
        $this->route->addRoute('user/authorization', UserAuth::class, 'authorization');
        /** @uses \App\Controller\User::indexAction() */
        $this->route->addRoute('user/index', UserIndex::class, 'index');
        /** @uses \App\Controller\User::baseAction() */
        $this->route->addRoute('', UserIndex::class, 'index');
        /** @uses \App\Controller\Blog::indexAction() */
        $this->route->addRoute('blog', BlogIndex::class, 'index');
        /** @uses \App\Controller\Blog::getAllAction() */
        $this->route->addRoute('blog/getAll', BlogGet::class, 'getAll');
        /** @uses \App\Controller\Blog::sendAction() */
        $this->route->addRoute('blog/send', BlogSend::class, 'send');
        /** @uses \App\Controller\Blog::indexAction() */
        $this->route->addRoute('user/delete', UserDelete::class, 'delete');
        /** @uses \App\Controller\Blog::indexAction() */
        $this->route->addRoute('blog/admin', BlogAdmin::class, 'admin');
        /** @uses \App\Controller\User::authorizationAction() */
        $this->route->addRoute('blog/delete', BlogDelete::class, 'delete');
        /** @uses \App\Controller\User::authorizationAction() */
        $this->route->addRoute('user/update', UserChange::class, 'change');
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

    public function eloquentInit(): Capsule
    {
        $manager = new Capsule();

        $manager->addConnection([
            'driver' => 'mysql',
            'host' => HOST_NAME,
            'database' => DB_NAME,
            'username' => USER_NAME,
            'password' => PASSWORD,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ]);

        $manager->setAsGlobal();

        $manager->bootEloquent();
        $manager->getConnection('default')->enableQueryLog();

        return $manager;
    }
}
