<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @property int              $id
 * @property string           $output_name
 * @property string           $driver_name
 * @property string           $driver_config
 * @property bool             $is_active
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class Output extends Model
{
    use AsSource;
    use Filterable;
    use Attachable;

    protected $casts = [
        'id' => 'integer',
        'output_name' => 'string',
        'driver_name' => 'string',
        'driver_config' => 'string',
        'is_active' => 'boolean',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected $attributes = [
        'id' => null,
        'output_name' => '',
        'driver_name' => '',
        'driver_config' => "{\n    \n}",
        'is_active' => false,
        'created_at' => null,
        'updated_at' => null,
    ];

    protected $allowedSorts = [
        'id',
        'output_name',
        'driver_name',
        'is_active'
    ];

    protected $allowedFilters = [
        'id',
        'output_name',
        'driver_name',
        'is_active'
    ];
}
