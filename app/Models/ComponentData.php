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

    protected $casts = [
        'id' => 'integer',
        'component' => 'string',
        'field' => 'string',
        'value' => 'string',
    ];

    protected $attributes = [
        'id' => null,
        'component' => '',
        'field' => '',
        'value' => null,
    ];

    protected $fillable = [
        'component',
        'field',
        'value',
    ];

    protected $allowedSorts = [
        'id',
        'component',
        'field' ,
        'value',
    ];

    protected $allowedFilters = [
        'id',
        'component',
        'field' ,
        'value',
    ];
}
