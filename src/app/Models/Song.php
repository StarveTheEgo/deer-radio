<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Screen\AsSource;

/**
 * @property int              $id
 * @property string           $title
 * @property Author|null      $author
 * @property Album|null       $album
 * @property Label|null       $label
 * @property int              $year
 * @property string           $source
 * @property int              $tempo
 * @property int              $song_attachment_id
 * @property ?CarbonImmutable $played_at
 * @property ?CarbonImmutable $finished_at
 * @property int              $played_count
 * @property bool             $is_active
 * @property int              $volume
 * @property string           $unsplash_search_query
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class Song extends Model
{
    use AsSource;
    use Filterable;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        // @note author_id relation
        // @note album_id relation
        // @note label_id relation
        'year' => 'integer',
        'source' => 'string',
        'tempo' => 'integer',
        'song_attachment_id' => 'integer',
        'played_at' => 'immutable_datetime',
        'finished_at' => 'immutable_datetime',
        'played_count' => 'integer',
        'is_active' => 'boolean',
        'volume' => 'integer',
        'unsplash_search_query' => 'string',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    /**
     * @var array<string, scalar|null>
     */
    protected $attributes = [
        'id' => null,
        'title' => '',
        // @note author_id relation
        // @note album_id relation
        // @note label_id relation
        'year' => 1993,
        'source' => '',
        'tempo' => 0,
        'song_attachment_id' => null,
        'played_at' => null,
        'finished_at' => null,
        'played_count' => 0,
        'is_active' => true,
        'volume' => 100,
        'unsplash_search_query' => '',
        'created_at' => null,
        'updated_at' => null,
    ];

    /**
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'year',
        'source',
        'tempo',
        'song_attachment_id',
        'played_at',
        'finished_at',
        'played_count',
        'is_active',
        'volume',
        'unsplash_search_query',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<string>
     */
    protected $allowedSorts = [
        'id',
        'title',
        'year',
        'tempo',
        'played_at',
        'finished_at',
        'played_count',
        'is_active',
        'volume',
        'unsplash_search_query',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<string>
     */
    protected $allowedFilters = [
        'id' => Where::class,
        'title' => Like::class,
        'year' => Where::class,
        'source' => Like::class,
        'tempo' => Where::class,
        'played_at' => WhereDateStartEnd::class,
        'finished_at' => WhereDateStartEnd::class,
        'played_count' => Where::class,
        'is_active' => Where::class,
        'volume' => Where::class,
        'unsplash_search_query' => Like::class,
        'created_at' => WhereDateStartEnd::class,
        'updated_at' => WhereDateStartEnd::class,
    ];

    /**
     * @return BelongsTo<Author, Song>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    /**
     * @return BelongsTo<Album, Song>
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'album_id');
    }

    /**
     * @return BelongsTo<Label, Song>
     */
    public function label(): BelongsTo
    {
        return $this->belongsTo(Label::class, 'label_id');
    }
}
