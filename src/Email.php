<?php

namespace Core;

class Email implements IField
{
    private ?string $email;

    public function __construct(?string $email)
    {
        $this->email = $email;
        return $this;
    }

    public function get(): ?string
    {
        return $this->email;
    }

    public function set(string $value): void
    {
        $this->email = $value;
    }
}
