<?php

declare(strict_types=1);

namespace App\Components\Output\Interfaces;

interface ChatClientInterface
{
    public function sendMessage(string $messageText, string $channel = null) : void;
}
