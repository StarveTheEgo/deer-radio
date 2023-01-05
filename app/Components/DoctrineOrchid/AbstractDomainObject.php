<?php

declare(strict_types=1);

namespace App\Components\DoctrineOrchid;

use ArrayAccess;
use ReturnTypeWillChange;

abstract class AbstractDomainObject implements ArrayAccess
{
    use DoctrineAsSource;

    #[ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    #[ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        $this->$offset = null;
    }
}
