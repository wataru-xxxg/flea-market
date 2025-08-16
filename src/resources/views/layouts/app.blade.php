<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
    @yield('livewire')
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <a href="/"><img src="/logo.svg" alt="ロゴマーク" class="header-logo"></a>
            @yield('search')
            @yield('navigation')
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>