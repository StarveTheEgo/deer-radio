<?php

declare(strict_types=1);

namespace App\Components\DoctrineOrchid\Exceptions;

use App\Components\DoctrineOrchid\AbstractDomainObject;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Arr;

/**
 * @template TObject of AbstractDomainObject
 */
class ObjectNotFoundException extends RecordsNotFoundException
{
    /**
     * @var class-string<TObject>
     */
    protected string $object;

    /**
     * @var array<int, int|string>
     */
    protected array $ids = [];

    /**
     * @param class-string<TObject> $object
     * @param array<int, int|string>|int|string  $ids
     * @return $this
     */
    public function setObjectInfo(string $object, array $ids = []) : ObjectNotFoundException
    {
        $this->object = $object;
        $this->ids = Arr::wrap($ids);

        $this->message = "No query results for object [{$object}]";

        if (count($this->ids) > 0) {
            $this->message .= ' '.implode(', ', $this->ids);
        } else {
            $this->message .= '.';
        }

        return $this;
    }

    /**
     * @return class-string<TObject>
     */
    public function getObjectClass(): string
    {
        return $this->object;
    }

    /**
     * @return array<int, int|string>
     */
    public function getIds(): array
    {
        return $this->ids;
    }
}
