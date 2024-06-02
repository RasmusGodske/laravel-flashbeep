<?php

namespace RasmusGodske\FlashBeep\FlashMessage;

abstract class AbstractFlashMessage implements FlashMessageInterface
{
    protected $attributes = [];

    public function addAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}