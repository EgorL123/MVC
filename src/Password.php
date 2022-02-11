<?php

namespace Core;

class Password implements IField
{
    private ?string $password;

    public function __construct(?string $password)
    {
        $this->password = $password;
        return $this;
    }

    public function get(): ?string
    {
        return $this->password;
    }

    public function set(string $value): void
    {
        $this->password = $value;
    }
}
