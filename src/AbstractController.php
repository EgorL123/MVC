<?php

namespace Core;

use App\Model\User;
use Core\RedirectException;

abstract class AbstractController
{
    private Twig $view;



    public function redirect(string $url)
    {
         throw new RedirectException($url);
    }

    public function setView(Twig $twig): void
    {
        $this->view = $twig;
    }

    public function getView(): \Core\Twig
    {
        return $this->view;
    }


}
