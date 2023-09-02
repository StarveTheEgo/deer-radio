<?php

declare(strict_types=1);

namespace App\Components\Output\Interfaces;

use App\Components\Google\Output\GoogleChatClient;

interface ChatClientAwareInterface
{
    public function getChatClient() : GoogleChatClient;
}
