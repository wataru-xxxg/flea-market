<div class="nav-links">
    @if (Auth::check())
    <form class="logout-form" action="/logout" method="post">
        @csrf
        <button class="logout-button">ログアウト</button>
    </form>
    @else
    <a href="/login" class="nav-link">ログイン</a>
    @endif
    <a href="/mypage" class="nav-link">マイページ</a>
    <a href="/sell" class="listing-button">出品</a>
</div>