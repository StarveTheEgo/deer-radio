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

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    protected $attributes = [
        'id' => null,
        'name' => '',
    ];

    protected $fillable = [
        'name',
    ];

    protected $allowedSorts = [
        'id',
        'name',
    ];

    protected $allowedFilters = [
        'id',
        'name',
    ];

    public function labelLinks(): HasMany
    {
        return $this->hasMany(LabelLink::class, 'label_id');
    }
}
