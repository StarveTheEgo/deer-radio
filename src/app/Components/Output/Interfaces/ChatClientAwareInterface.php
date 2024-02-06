<?php

declare(strict_types=1);

namespace App\Components\Output\Interfaces;

interface ChatClientAwareInterface
{
    /**
     * @return class-string<ChatClientInterface>
     */
    public static function getChatClientClassName() : string;
}
