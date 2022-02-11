<?php

namespace Core;

use Twig_Loader_Filesystem;

class Twig implements IView
{
    private string $template;

    private array $params;

    private string $fileName;

    private bool $cache;

    private bool $debug;

    private bool $strictVariables;


    public function __construct(string $tmp)
    {
        $this->template = $tmp . DIRECTORY_SEPARATOR;
    }

    /**
     * @param mixed[] $params
     */
    public function setData(
        array $params,
        string $fileName,
        bool $cache = false,
        bool $debug = false,
        bool $strictVariables = false
    ): self {
        $this->params = $params;
        $this->fileName = $fileName;
        $this->cache = $cache;
        $this->debug = $debug;
        $this->strictVariables = $strictVariables;
        return $this;
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(): string
    {
        $twigLoaderFilesystem = new Twig_Loader_Filesystem($this->template);

        $environment = new \Twig\Environment($twigLoaderFilesystem, [
            'cache' => $this->cache,
            'debug' => $this->debug,
            'strict_variables' => $this->strictVariables
        ]);

        return $environment->render($this->fileName, $this->params);
    }
}
