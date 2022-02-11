<?php

namespace Core;

class UserId implements \Core\IField
{
    private ?string $value;

    public function __construct(?string $id)
    {
        $this->value = $id;
        return $this;
    }


    public function get(): ?string
    {
        return $this->value;
    }

    public function set(string $value): void
    {
        $this->value = $value;
    }
}
