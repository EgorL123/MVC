<?php

namespace Core;

class MessageId implements IField
{
    public ?string $id;

    public function __construct(?string $id)
    {
        $this->id = $id;
    }


    public function get(): ?string
    {
        return $this->id;
    }

    public function set(string $value): void
    {
        $this->id = $value;
    }
}
