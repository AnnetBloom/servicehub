<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ServiceHub')</title>
</head>
<body>

<header>
    <strong>ServiceHub</strong>

    @auth
        <span>{{ auth()->user()->name }}</span>

        <form method="POST" action="/logout" style="display: inline;">
            @csrf
            <button type="submit">Выйти</button>
        </form>
    @endauth
</header>

<nav>
    <a href="/dashboard">Dashboard</a>
    <a href="/companies">Компании</a>
    <a href="/sites">Площадки</a>
    <a href="/devices">Оборудование</a>
    <a href="/tickets">Заявки</a>
</nav>

<main>
    @yield('content')
</main>

</body>
</html>