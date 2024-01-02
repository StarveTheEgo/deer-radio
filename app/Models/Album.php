<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @property int              $id
 * @property Author           $author
 * @property string           $title
 * @property int              $year
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class Album extends Model
{
    use AsSource;
    use Filterable;
    use Attachable;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        // @note author_id relation
        'title' => 'string',
        'year' => 'integer',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    /**
     * @var array<string, scalar|null>
     */
    protected $attributes = [
        'id' => null,
        // @note author_id relation
        'title' => '',
        'year' => 1993,
        'created_at' => null,
        'updated_at' => null,
    ];

    /**
     * @var array<string>
     */
    protected $allowedSorts = [
        'id',
        'title',
        'year',
    ];

    protected $allowedFilters = [
        'id',
        'title',
        'year',
    ];

    /**
     * @return BelongsTo<Author, Album>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
