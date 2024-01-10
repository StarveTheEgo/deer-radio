<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @property int              $id
 * @property string           $image_url
 * @property string           $reason
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class Photoban extends Model
{
    use AsSource;
    use Filterable;
    use Attachable;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'image_url' => 'string',
        'reason' => 'string',
    ];

    /**
     * @var array<string, scalar|null>
     */
    protected $attributes = [
        'id' => null,
        'image_url' => '',
        'reason' => '',
    ];

    /**
     * @var array<string>
     */
    protected $fillable = [
        'image_url',
        'reason',
    ];

    /**
     * @var array<string>
     */
    protected $allowedSorts = [
        'id',
        'image_url',
    ];

    /**
     * @var array<string>
     */
    protected $allowedFilters = [
        'id',
        'image_url',
        'reason',
    ];

}
