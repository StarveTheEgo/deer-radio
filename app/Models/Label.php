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
 * @property string           $title
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class Label extends Model
{
    use AsSource;
    use Filterable;
    use Attachable;

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
    ];

    protected $attributes = [
        'id' => null,
        'title' => '',
    ];

    protected $fillable = [
        'name',
        'is_active',
        'unsplash_search_query',
    ];

    protected $allowedSorts = [
        'id',
        'title',
    ];

    protected $allowedFilters = [
        'id',
        'title',
    ];

    public function labelLinks(): HasMany
    {
        return $this->hasMany(LabelLink::class, 'label_id');
    }
}
