<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class Author extends Model
{
    use AsSource;
    use Filterable;
    use Attachable;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'is_active' => 'boolean',
        'played_at' => 'immutable_datetime',
        'finished_at' => 'immutable_datetime',
        'played_count' => 'integer',
        'unsplash_search_query' => 'string',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    /**
     * @var array<string, scalar|null>
     */
    protected $attributes = [
        'id' => null,
        'name' => '',
        'is_active' => true,
        'played_at' => null,
        'finished_at' => null,
        'played_count' => 0,
        'unsplash_search_query' => '',
        'created_at' => null,
        'updated_at' => null,
    ];

    /**
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'is_active',
        'unsplash_search_query',
    ];

    /**
     * @var array<string>
     */
    protected $allowedSorts = [
        'id',
        'name',
        'is_active',
        'played_at',
        'finished_at',
        'played_count',
        'unsplash_search_query',
    ];

    /**
     * @var array<string>
     */
    protected $allowedFilters = [
        'id',
        'name',
        'unsplash_search_query',
    ];

    /**
     * @return HasMany<AuthorLink, Author>
     */
    public function authorLinks(): HasMany
    {
        return $this->hasMany(AuthorLink::class, 'author_id');
    }

    /**
     * @return HasMany<Album, Author>
     */
    public function albums(): HasMany
    {
        return $this->hasMany(Album::class, 'author_id');
    }

}
