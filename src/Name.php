<?php

namespace Core;

class Name implements IField
{
    private ?string $name;

    public function __construct(?string $name)
    {
        $this->name = $name;
        return $this;
    }


    public function get(): ?string
    {
        return $this->name;
    }

    public function set(string $value): void
    {
        $this->name = $value;
    }
}
