<div>
    <img src="
    @if ($image)
    {{ $image->temporaryUrl() }}
    @endif
    " alt="商品画像" class="item-image">
    <input type="file" name="image" class="image-select-button" wire:model="image">
</div>