<?php

namespace Core;

interface IField
{
    public function get();

    public function set(string $value);
}
