<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(["resources/sass/app.scss", "resources/js/bootstrap.js"])
    <title>Планирование полетов</title>
</head>
<body>
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

<div class="d-flex">
    <!-- Боковая панель -->
    <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px; min-height: 100vh;">
        <svg class="bi pe-none me-2" width="40" height="32" aria-hidden="true">
            <use xlink:href="#bootstrap"></use>
        </svg>
        <span class="fs-4">Панель</span>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="/flight" class="nav-link text-white">
                    <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                        <use xlink:href="#speedometer2"></use>
                    </svg>
                    Дашборд
                </a>
            </li>
            <li>
                <a href="/flight/active" class="nav-link text-white">
                    <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                        <use xlink:href="#table"></use>
                    </svg>
                    Активные
                </a>
            </li>
            <li>
                <a href="/flight/flightcreate" class="nav-link text-white">
                    <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                        <use xlink:href="#grid"></use>
                    </svg>
                    Зарегистрировать рейс
                </a>
            </li>
            <li>
                <a href="/flight/history" class="nav-link text-white">
                    <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                        <use xlink:href="#grid"></use>
                    </svg>
                    История
                </a>
            </li>
            <li>
                <a href="/flight/assign-crew" class="nav-link text-white">
                    <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                        <use xlink:href="#grid"></use>
                    </svg>
                    Назначить сотрудников
                </a>
            </li>
            <li>
                <a href="/flight/aircraftcreate" class="nav-link text-white">
                    <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                        <use xlink:href="#grid"></use>
                    </svg>
                    Зарегистрировать новый самолет в системе
                </a>
            </li>
        </ul>
        <hr>
    </div>

    <!-- Основной контент -->
    <main class="flex-grow-1">
        @yield('main_content')
    </main>
</div>
</body>
</html>
