<?php

namespace Core;

use Twig_Loader_Filesystem;

class Twig
{
    public function render
    (string $template, array $params, string $fileName, bool $cache = false, bool $debug = false,
     bool $strictVariables = false): string
    {
        $twigLoaderFilesystem = new Twig_Loader_Filesystem($template);

        $environment = new \Twig\Environment($twigLoaderFilesystem, [
            'cache' => $cache,
            'debug' => $debug,
            'strict_variables' => $strictVariables
        ]);

        return $environment->render($fileName, $params);
    }


}