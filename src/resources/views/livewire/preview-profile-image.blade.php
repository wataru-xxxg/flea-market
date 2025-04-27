<div>
    @if ($image)
    <img src="{{ $image->temporaryUrl() }}" alt="プロフィール画像" class="profile-image">
    @elseif($user->profile === null)
    <img src="" alt="プロフィール画像" class="profile-image">
    @elseif($user->profile->getImagePath() === null)
    <img src="" alt="プロフィール画像" class="profile-image">
    @else
    <img src="{{ asset(Storage::url($user->profile->getImagePath())) }}" alt="プロフィール画像" class="profile-image">
    @endif

    <input type="file" name="image" class="image-select-button" wire:model="image">
</div>