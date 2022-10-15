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
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class Photoban extends Model
{
    use AsSource;
    use Filterable;
    use Attachable;

    protected $casts = [
        'id' => 'integer',
        'image_url' => 'string',
    ];

    protected $attributes = [
        'id' => null,
        'image_url' => '',
    ];

    protected $fillable = [
        'image_url',
    ];

    protected $allowedSorts = [
        'id',
        'image_url',
    ];

    protected $allowedFilters = [
        'id',
        'image_url',
    ];

}
