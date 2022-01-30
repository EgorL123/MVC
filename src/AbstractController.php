<?php

namespace Core;

use App\Model\User;
use Core\RedirectException;

abstract class AbstractController
{
    private \Core\View $view;


    public function redirect(string $url)
    {
         throw new RedirectException($url);
    }

    public function setView(View $view): void
    {
        $this->view = $view;
    }

    public function getView(): \Core\View
    {
        return $this->view;
    }

    public function setUser(User $user): void
    {
    }
}
