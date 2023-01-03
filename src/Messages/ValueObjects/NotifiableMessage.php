<?php

namespace Amir\Notifier\Messages\ValueObjects;

interface NotifiableMessage
{
    public function getMessage(): array;
}