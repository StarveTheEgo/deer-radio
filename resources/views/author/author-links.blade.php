@php use App\Orchid\Resources\AuthorLinkResource; @endphp
<ul>
    @foreach ($author_links as $author_link)
        <li>
            <a href="{{ route('platform.resource.edit', ['resource' => AuthorLinkResource::uriKey(), 'id' => $author_link->id]) }}">[{{ $author_link->id }}]</a>
            <a href="{{ $author_link->url }}" target="_blank">{{ $author_link->url }}</a>
        </li>
    @endforeach
</ul>
