<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ComponentData extends Model
{
    use AsSource;
    use Filterable;
    use Attachable;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'component' => 'string',
        'field' => 'string',
        'value' => 'string',
    ];

    /**
     * @var array<string, scalar|null>
     */
    protected $attributes = [
        'id' => null,
        'component' => '',
        'field' => '',
        'value' => null,
    ];

    /**
     * @var array<string>
     */
    protected $fillable = [
        'component',
        'field',
        'value',
    ];

    /**
     * @var array<string>
     */
    protected $allowedSorts = [
        'id',
        'component',
        'field' ,
        'value',
    ];

    /**
     * @var array<string>
     */
    protected $allowedFilters = [
        'id',
        'component',
        'field' ,
        'value',
    ];
}
