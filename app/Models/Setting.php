<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @property int              $id
 * @property SettingGroup     $group
 * @property string           $name
 * @property string           $description
 * @property string           $value
 * @property bool             $is_encrypted
 * @property string           $field_type
 * @property ?array           $field_options
 * @property int              $ord
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 */
class Setting extends Model
{
    use AsSource;
    use Filterable;
    use Attachable;

    protected $casts = [
        'id' => 'integer',
        // @note group_id relation
        'name' => 'string',
        'description' => 'string',
        'value' => 'string',
        'is_encrypted' => 'boolean',
        'field_type' => 'string',
        'field_options' => 'json',
        'ord' => 'integer',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected $attributes = [
        'id' => null,
        // @note group_id relation
        'name' => '',
        'description' => '',
        'value' => '',
        'is_encrypted' => false,
        'field_type' => 'text',
        'field_options' => null,
        'ord' => 1,
        'created_at' => null,
        'updated_at' => null,
    ];

    protected $fillable = [
        'name',
        'description',
        'value',
        'is_encrypted',
        'field_type',
        'field_options',
        'ord',
        'created_at',
        'updated_at',
    ];

    // @todo check if it works
    protected $allowedFilters = [
        'name',
    ];
}
