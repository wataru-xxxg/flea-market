<div class="tab-menu">
    <form action="/?page=mylist" method="post">
        @csrf
        @if (is_null($search))
        <input wire:model="search" type="hidden" name="search" value="">
        @else
        <input wire:model="search" type="hidden" name="search" value="{{ $search }}">
        @endif
        @if (isset($mylist))
        <input type="submit" value="おすすめ" class="recommend" formaction="/">
        <input type="submit" value="マイリスト" class="mylist active" formaction="/?page=mylist">
        @else
        <input type="submit" value="おすすめ" class="recommend active" formaction="/">
        <input type="submit" value="マイリスト" class="mylist" formaction="/?page=mylist">
        @endif
    </form>
</div>