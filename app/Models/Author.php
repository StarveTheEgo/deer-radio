<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @property int              $id
 * @property string           $name
 * @property bool             $is_active
 * @property ?CarbonImmutable $played_at
 * @property ?CarbonImmutable $finished_at
 * @property int              $played_count
 * @property string           $unsplash_search_query
 */
class Author extends Model
{
    use AsSource;
    use Filterable;
    use Attachable;

    public $timestamps = false;

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'is_active' => 'boolean',
        'played_at' => 'immutable_datetime',
        'finished_at' => 'immutable_datetime',
        'played_count' => 'integer',
        'unsplash_search_query' => 'string',
    ];

    protected $attributes = [
        'id' => null,
        'name' => '',
        'is_active' => true,
        'played_at' => null,
        'finished_at' => null,
        'played_count' => 0,
        'unsplash_search_query' => '',
    ];

    protected $fillable = [
        'name',
        'is_active',
        'unsplash_search_query',
    ];

    protected $allowedSorts = [
        'id',
        'name',
        'is_active',
        'played_at',
        'finished_at',
        'played_count',
        'unsplash_search_query',
    ];

    protected $allowedFilters = [
        'id',
        'name',
        'unsplash_search_query',
    ];
}
