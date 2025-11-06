@extends('flightmanager.flightlayout')

@section('main_content')
    <div class="container-fluid p-4">
        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">📊 История полетов</h4>
            <span class="badge bg-secondary fs-6">Всего: {{ $flights->total() }}</span>
        </div>

        <!-- Фильтры -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Фильтры истории</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('flight.history') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="flight_number" class="form-label">Номер рейса</label>
                        <input type="text" name="flight_number" id="flight_number" class="form-control"
                               placeholder="Введите номер рейса" value="{{ request('flight_number') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="departure" class="form-label">Вылет из</label>
                        <select name="departure" id="departure" class="form-select">
                            <option value="">Все пункты вылета</option>
                            @foreach($departures as $departure)
                                <option value="{{ $departure }}" {{ request('departure') == $departure ? 'selected' : '' }}>
                                    {{ $departure }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="arrival" class="form-label">Прилет в</label>
                        <select name="arrival" id="arrival" class="form-select">
                            <option value="">Все пункты прилета</option>
                            @foreach($arrivals as $arrival)
                                <option value="{{ $arrival }}" {{ request('arrival') == $arrival ? 'selected' : '' }}>
                                    {{ $arrival }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="aircraft_id" class="form-label">Самолет</label>
                        <select name="aircraft_id" id="aircraft_id" class="form-select">
                            <option value="">Все самолеты</option>
                            @foreach($aircrafts as $aircraft)
                                <option value="{{ $aircraft->id }}" {{ request('aircraft_id') == $aircraft->id ? 'selected' : '' }}>
                                    {{ $aircraft->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Применить фильтры
                            </button>
                            <a href="{{ route('flight.history') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Сбросить
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Статистика -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Всего полетов</h6>
                                <h3 class="mb-0">{{ $flights->total() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-plane fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Успешно завершено</h6>
                                <h3 class="mb-0">{{ $flights->where('flight_status_id', 4)->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Отменено</h6>
                                <h3 class="mb-0">{{ $flights->where('flight_status_id', 5)->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-times-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Общее время</h6>
                                <h3 class="mb-0">{{ $flights->sum('flight_time') }}ч</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Таблица истории полетов -->
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">История выполненных полетов</h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-success">Завершено: {{ $flights->where('flight_status_id', 4)->count() }}</span>
                    <span class="badge bg-danger">Отменено: {{ $flights->where('flight_status_id', 5)->count() }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($flights->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                            <tr>
                                <th>№ Рейса</th>
                                <th>Самолет</th>
                                <th>Маршрут</th>
                                <th>Время полета</th>
                                <th>Статус</th>
                                <th>Дата выполнения</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($flights as $flight)
                                <tr>
                                    <td>
                                        <strong>{{ $flight->flight_number }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $flight->aircraft->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="text-start">
                                                <strong>{{ $flight->departure }}</strong>
                                                <br>
                                                <small class="text-muted">Вылет</small>
                                            </div>
                                            <div class="mx-3">
                                                <i class="fas fa-arrow-right text-muted"></i>
                                            </div>
                                            <div class="text-start">
                                                <strong>{{ $flight->arrival }}</strong>
                                                <br>
                                                <small class="text-muted">Прилет</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="fw-bold">{{ $flight->flight_time }}</div>
                                            <small class="text-muted">часов</small>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                1 => 'bg-warning', // Готовится
                                                2 => 'bg-info',    // Запланирован
                                                3 => 'bg-success', // В полете
                                                4 => 'bg-secondary', // Завершен
                                                5 => 'bg-danger'   // Отменен
                                            ];
                                            $color = $statusColors[$flight->flight_status_id] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $color }}">
                                            {{ $flight->flightStatus->name ?? 'Неизвестно' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $flight->updated_at ? $flight->updated_at->format('d.m.Y H:i') : 'Нет данных' }}
                                        </small>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Пагинация -->
                    @if($flights->hasPages())
                        <div class="mt-4">
                            {{ $flights->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">История полетов пуста</h5>
                        <p class="text-muted">Здесь будут отображаться завершенные и отмененные рейсы</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        .badge {
            font-size: 0.75em;
            padding: 0.4em 0.6em;
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
    </style>

    <script>
        // Инициализация tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endsection
