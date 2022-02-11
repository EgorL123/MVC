<?php

namespace Core;

class BlogMessageText implements IField
{
    private ?string $text;

    public function __construct(?string $text)
    {
        $this->text = $text;
    }

    public function get(): ?string
    {
        return $this->text;
    }

    public function set(string $value): void
    {
        $this->text = $value;
    }
}
