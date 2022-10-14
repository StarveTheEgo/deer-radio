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

    protected $casts = [
        'id' => 'integer',
        // @note author_id relation
        'title' => 'string',
        'year' => 'integer',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected $attributes = [
        'id' => null,
        // @note author_id relation
        'title' => '',
        'year' => 1993,
        'created_at' => null,
        'updated_at' => null,
    ];

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

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
