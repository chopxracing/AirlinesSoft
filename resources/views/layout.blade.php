<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(["resources/sass/app.scss", "resources/js/bootstrap.js"])
    <title>@yield('title')</title>
</head>
<header class="d-flex justify-content-center py-3 bg-dark">
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a href="/" class="nav-link active" aria-current="page">Главная</a>
        </li>
        <li class="nav-item">
            <a href="/crew" class="nav-link">Управление персоналом</a>
        </li>
        <li class="nav-item">
            <a href="/flight" class="nav-link">Планирование полетов</a>
        </li>
        <li class="nav-item">
            <a href="/work" class="nav-link">Экипаж</a>
        </li>
        <li class="nav-item">
            <a href="/reports" class="nav-link">Отчетность</a>
        </li>
    </ul>
</header>

<body>
    @yield('main_content')
</body>
</html>
