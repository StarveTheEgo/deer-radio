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
 * @property Label            $label
 * @property string           $url
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class LabelLink extends Model
{
    use AsSource;
    use Filterable;
    use Attachable;

    protected $casts = [
        'id' => 'integer',
        // @note label_id relation
        'url' => 'string',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected $attributes = [
        'id' => null,
        'url' => '',
        'created_at' => null,
        'updated_at' => null,
    ];

    protected $allowedSorts = [
        'id',
    ];

    protected $allowedFilters = [
        'id',
    ];

    public function label(): BelongsTo
    {
        return $this->belongsTo(Label::class, 'label_id');
    }
}
