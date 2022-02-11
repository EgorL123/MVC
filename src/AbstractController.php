<?php

namespace Core;

use App\Model\User;
use Core\RedirectException;

abstract class AbstractController
{
    protected string $pathToPattern;

    private IView $view;

    protected $container;


    public function redirect(string $url)
    {
         throw new RedirectException($url);
    }

    public function setView(IView $view): void
    {
        $this->view = $view;
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getView(): IView
    {
        return $this->view;
    }

    /**
     * Возвращает готовый шаблон
     * @param array $data
     * @param string $fileName
     * @param bool $cache
     * @param bool $debug
     * @param bool $strictVariables
     * @return string
     */
    public function getPattern(
        array $data,
        string $fileName,
        bool $cache = false,
        bool $debug = false,
        bool $strictVariables = false
    ): string {
        return $this->getView()->setData($data, $this->pathToPattern . $fileName, 0, 0)->render();
    }
}
