@php use App\Orchid\Resources\LabelLinkResource; @endphp
<ul>
    @foreach ($label_links as $label_link)
        <li>
            <a href="{{ $label_link->url }}" target="_blank">{{ $label_link->url }}</a>
            | <a href="{{ route('platform.resource.edit', ['resource' => LabelLinkResource::uriKey(), 'id' => $label_link->id]) }}">Edit</a>
        </li>
    @endforeach
</ul>
