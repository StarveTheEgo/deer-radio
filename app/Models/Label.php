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
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class Label extends Model
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
    ];

    /**
     * @var array<string, scalar|null>
     */
    protected $attributes = [
        'id' => null,
        'name' => '',
    ];

    /**
     * @var array<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @var array<string>
     */
    protected $allowedSorts = [
        'id',
        'name',
    ];

    /**
     * @var array<string, string>
     */
    protected $allowedFilters = [
        'id',
        'name',
    ];

    /**
     * @return HasMany<LabelLink, Label>
     */
    public function labelLinks(): HasMany
    {
        return $this->hasMany(LabelLink::class, 'label_id');
    }
}
