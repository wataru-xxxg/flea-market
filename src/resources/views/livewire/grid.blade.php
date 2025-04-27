<div>
    <div class="items-grid">
        @foreach ($results as $result)
        <figure class="item-card">
            <a href="/item/{{ $result->id }}"><img src="{{ asset(Storage::url($result->image_path)) }}" alt="商品画像" class="item-image @if ($result->purchased === 1) grayed-out @endif"></a>
            <figcaption class="item-name">{{ $result->name }}</figcaption>
            @if ($result->purchased === 1)
            <p class="sold">Sold</p>
            <p>test</p>
            @endif
        </figure>
        @endforeach
    </div>
</div>