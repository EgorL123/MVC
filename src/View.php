<?php

namespace Core;

use App\Controller\User;

/**
 * @property-read User $user
 */
class View
{
    private $template;
    private array $data = [];

    public function __construct()
    {
        $this->template = PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "View" . DIRECTORY_SEPARATOR;
    }

    public function assign($name, $value): void
    {
        $this->data[$name] = $value;
    }

    public function render(string $tmp, array $data = [])
    {
        $this->data += $data;

        ob_start();
        include $this->template . $tmp;

        return ob_get_clean();
    }

    public function __get($var)
    {
        return $this->data[$var] ?? null;
    }
}
