<?php

namespace App\Orchid\Resources;

use App\Components\Photoban\Entity\Photoban as DoctrinePhotoban;
use App\Models\Photoban;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class PhotobanResource extends AbstractResource
{
    public static $model = Photoban::class;

    public static function icon(): string
    {
        return 'direction';
    }

    public static function description(): ?string
    {
        return __('Photo links that are not allowed to appear on the Deer Radio!');
    }

    public function fields(): array
    {
        return [
            Input::make('image_url')
                ->title(__('Image URL'))
                ->required(),

            Input::make('reason')
                ->title(__('Reason'))
                ->placeholder('Please specify a reason!')
                ->required(),
        ];
    }

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id', __('ID'))
                ->sort()
                ->filter(Input::make()),

            TD::make('image_url', __('Image URL'))
                ->sort()
                ->filter(Input::make()),

            TD::make('reason', __('Reason'))
                ->filter(Input::make()),
        ];
    }

    /**
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('id', __('ID')),

            Sight::make('image_url', __('Image URL')),

            Sight::make('reason', __('Reason')),

            Sight::make('created_at', __('Date of creation')),

            Sight::make('updated_at', __('Update date')),
        ];
    }

    /**
     * @param Model|Photoban $model
     * @return array
     */
    public function rules(Model $model): array
    {
        return [
            'image_url' => [
                'required',
                'url',
                Rule::unique(DoctrinePhotoban::class, 'imageUrl')->ignore($model->id),
            ],

            'reason' => [
                'required',
            ],
        ];
    }

    public function filters(): array
    {
        return [];
    }
}
