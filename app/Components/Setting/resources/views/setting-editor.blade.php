@php
    use App\Components\Setting\Entity\Setting;
    use Orchid\Screen\Actions\Button;
    use Orchid\Screen\Field;
@endphp
@php /** @var Setting $setting */ @endphp
@php /** @var Field $valueEditField */ @endphp
<div class="rounded bg-white mb-3 p-3">
    <div class="row justify-content-around">
        <div class="col-sm-10">
            <fieldset class="row g-0 mb-3">
                <legend class="text-black px-2 mt-2">
                    {{ $setting->getKey() }}
                    @if ($setting->isEncrypted())
                        <span title="This setting is encrypted in storage">
                            <x-orchid-icon path="lock"></x-orchid-icon>
                        </span>
                    @endif
                    <p class="small text-muted mt-2 mb-0">
                        {{ $setting->getDescription() }}
                    </p>
                </legend>
            </fieldset>
        </div>
        <nav class="col-sm-2">
            {{
                Button::make(__('Remove'))
                    ->class('btn btn-sm btn-danger')
                    ->icon('trash')
                    ->confirm(__('Are you sure you want to delete this setting? It can break the application!'))
                    ->method('remove')
                    ->parameters([
                        'key' => $setting->getKey(),
                    ])
            }}
        </nav>
    </div>

    <div class="row">
        {!! $valueEditField !!}
        <div class="row">
            <div class="col-sm">
                {{
                    Button::make(__('Save'))
                        ->class('btn btn-sm btn-primary')
                        ->method('save')
                }}
            </div>
        </div>
    </div>
</div>
