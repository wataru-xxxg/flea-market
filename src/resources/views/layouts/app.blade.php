<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
    @yield('js')
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <img src="/logo.svg" alt="ロゴマーク" class="header__logo">
            @yield('search')
            @yield('navigation')
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>