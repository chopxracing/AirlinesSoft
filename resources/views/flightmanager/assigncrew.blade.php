@extends('flightmanager.flightlayout')

@section('main_content')
    <div class="container-fluid p-4">
        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">👥 Назначение экипажа на полеты</h4>
            <span class="badge bg-primary fs-6">Активных полетов: {{ $activeFlights->count() }}</span>
        </div>

        <div class="row">
            <!-- Форма назначения -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Назначить сотрудника</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('flight.store.crew.assignment') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- Выбор полета -->
                                <div class="col-md-6">
                                    <label for="flight_id" class="form-label">Рейс *</label>
                                    <select name="flight_id" id="flight_id" class="form-select" required>
                                        <option value="">Выберите рейс</option>
                                        @foreach($activeFlights as $flight)
                                            <option value="{{ $flight->id }}">
                                                {{ $flight->flight_number }} - {{ $flight->departure }} → {{ $flight->arrival }}
                                                ({{ $flight->aircraft->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Выбор сотрудника -->
                                <div class="col-md-6">
                                    <label for="user_id" class="form-label">Сотрудник *</label>
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        <option value="">Выберите сотрудника</option>
                                        @foreach($availableCrew as $crew)
                                            <option value="{{ $crew->id }}">
                                                {{ $crew->name }} {{ $crew->surname }}
                                                ({{ $crew->position->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Роль в полете -->
                                <div class="col-md-6">
                                    <label for="role" class="form-label">Роль в полете *</label>
                                    <input type="text" name="role" id="role" class="form-control"
                                           placeholder="Например: Командир воздушного судна" required>
                                </div>

                                <!-- Часы налета -->
                                <div class="col-md-6">
                                    <label for="flight_hours" class="form-label">Часы налета *</label>
                                    <input type="number" name="flight_hours" id="flight_hours" class="form-control"
                                           min="1" max="24" value="2" required>
                                </div>

                                <!-- Кнопка -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check me-2"></i>Назначить на полет
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Статистика -->
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Статистика</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-primary">{{ $availableCrew->count() }}</h4>
                                <small class="text-muted">Доступных сотрудников</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success">{{ $flightHistories->total() }}</h4>
                                <small class="text-muted">Всего назначений</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Список назначений -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Текущие назначения</h5>
                    </div>
                    <div class="card-body">
                        @if($flightHistories->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                    <tr>
                                        <th>Сотрудник</th>
                                        <th>Рейс</th>
                                        <th>Роль</th>
                                        <th>Часы</th>
                                        <th>Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($flightHistories as $history)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $history->user->name }} {{ $history->user->surname }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $history->user->position->name ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <small>
                                                    <strong>{{ $history->flight->flight_number }}</strong>
                                                    <br>
                                                    {{ $history->flight->departure }} → {{ $history->flight->arrival }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $history->role }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $history->flight_hours }}ч</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('flight.remove.crew.assignment', $history->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Удалить назначение?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Пагинация -->
                            @if($flightHistories->hasPages())
                                <div class="mt-3">
                                    {{ $flightHistories->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Нет активных назначений</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Быстрое назначение -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Быстрое назначение</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($activeFlights->take(3) as $flight)
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $flight->flight_number }}</h6>
                                    <p class="card-text small mb-2">
                                        {{ $flight->departure }} → {{ $flight->arrival }}<br>
                                        <span class="text-muted">{{ $flight->aircraft->name ?? 'N/A' }}</span>
                                    </p>
                                    <select class="form-select form-select-sm mb-2 quick-assign-crew">
                                        <option value="">Выберите сотрудника</option>
                                        @foreach($availableCrew as $crew)
                                            <option value="{{ $crew->id }}" data-flight="{{ $flight->id }}">
                                                {{ $crew->name }} {{ $crew->surname }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary w-100 quick-assign-btn"
                                            data-flight="{{ $flight->id }}">
                                        Быстрое назначение
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border: 1px solid #dee2e6;
        }
        .table-sm td, .table-sm th {
            padding: 0.5rem;
        }
        .badge {
            font-size: 0.7em;
        }
        .quick-assign-crew {
            font-size: 0.8rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Быстрое назначение
            document.querySelectorAll('.quick-assign-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const flightId = this.getAttribute('data-flight');
                    const select = this.previousElementSibling;
                    const selectedOption = select.options[select.selectedIndex];
                    const userId = selectedOption.value;

                    if (!userId) {
                        alert('Пожалуйста, выберите сотрудника');
                        return;
                    }

                    // Здесь можно добавить AJAX запрос для быстрого назначения
                    alert(`Быстрое назначение на рейс ${flightId} сотрудника ${userId}`);
                });
            });

            // Автозаполнение роли при выборе сотрудника
            document.getElementById('user_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const position = selectedOption.text.match(/\(([^)]+)\)/);
                if (position) {
                    document.getElementById('role').value = position[1];
                }
            });
        });
    </script>
@endsection
