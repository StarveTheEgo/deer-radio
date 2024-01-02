<?php

declare(strict_types=1);

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
 * @property string           $url
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class AuthorLink extends Model
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
        'url' => 'string',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    /**
     * @var array<string, scalar|null>
     */
    protected $attributes = [
        'id' => null,
        'url' => '',
        'created_at' => null,
        'updated_at' => null,
    ];

    /**
     * @var array<string>
     */
    protected $allowedSorts = [
        'id',
    ];

    /**
     * @var array<string>
     */
    protected $allowedFilters = [
        'id',
    ];

    /**
     * @return BelongsTo<Author, AuthorLink>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
