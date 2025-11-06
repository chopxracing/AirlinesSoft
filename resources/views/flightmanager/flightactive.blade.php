@extends('flightmanager.flightlayout')

@section('main_content')
    <div class="container-fluid p-4">
        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">🛫 Активные полеты</h4>
            <span class="badge bg-primary fs-6">Всего: {{ $flights->total() }}</span>
        </div>

        <!-- Фильтры -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Фильтры</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('flight.active') }}" method="GET" class="row g-3">
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

                    <div class="col-md-3">
                        <label for="flight_status_id" class="form-label">Статус полета</label>
                        <select name="flight_status_id" id="flight_status_id" class="form-select">
                            <option value="">Все статусы</option>
                            @foreach($flightStatuses as $status)
                                <option value="{{ $status->id }}" {{ request('flight_status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Применить фильтры
                            </button>
                            <a href="{{ route('flight.active') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Сбросить
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Таблица полетов -->
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Список активных полетов</h5>
                <span class="badge bg-success">Активно: {{ $flights->count() }}</span>
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
                                                <small class="text-muted">{{ $flight->arrival }}</small>
                                            </div>
                                            <div class="mx-3">
                                                <i class="fas fa-arrow-right text-muted"></i>
                                            </div>
                                            <div class="text-start">
                                                <strong>{{ $flight->arrival }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $flight->departure }}</small>
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
                        <i class="fas fa-plane-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Активные полеты не найдены</h5>
                        <p class="text-muted">Попробуйте изменить параметры фильтрации</p>
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
    </style>
@endsection
