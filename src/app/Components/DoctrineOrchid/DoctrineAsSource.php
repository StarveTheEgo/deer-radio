<?php

declare(strict_types=1);

namespace App\Components\DoctrineOrchid;

trait DoctrineAsSource
{
    public function getContent(string $field)
    {
        // @todo find some optimal and safe way
        return $this->{$field};
    }
}
