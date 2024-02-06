<?php

declare(strict_types=1);

namespace App\Components\Output\Interfaces;

use App\Components\Output\Entity\Output;

interface ChatClientInterface
{
    public function sendMessage(Output $output, string $messageText, string $channel = null) : void;
}
