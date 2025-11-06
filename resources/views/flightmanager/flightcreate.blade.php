@extends('flightmanager.flightlayout')

@section('main_content')
    <div class="container-fluid p-4">
        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">✈️ Создание нового рейса</h4>
            <a href="{{ route('flight.active') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Назад к списку
            </a>
        </div>

        <!-- Форма создания рейса -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Данные рейса</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('flight.create') }}" method="POST" class="row g-3">
                    @csrf

                    <!-- Номер рейса -->
                    <div class="col-md-6">
                        <label for="flight_number" class="form-label">Номер рейса *</label>
                        <input type="text"
                               name="flight_number"
                               id="flight_number"
                               class="form-control @error('flight_number') is-invalid @enderror"
                               placeholder="Например: SU-1234"
                               value="{{ old('flight_number') }}"
                               required>
                        @error('flight_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Самолет -->
                    <div class="col-md-6">
                        <label for="aircraft_id" class="form-label">Самолет *</label>
                        <select name="aircraft_id"
                                id="aircraft_id"
                                class="form-select @error('aircraft_id') is-invalid @enderror"
                                required>
                            <option value="">Выберите самолет</option>
                            @foreach($aircrafts as $aircraft)
                                <option value="{{ $aircraft->id }}" {{ old('aircraft_id') == $aircraft->id ? 'selected' : '' }}>
                                    {{ $aircraft->name }} (Вместимость: {{ $aircraft->passenger_capacity }} пасс.)
                                </option>
                            @endforeach
                        </select>
                        @error('aircraft_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Пункт вылета -->
                    <div class="col-md-6">
                        <label for="departure" class="form-label">Пункт вылета *</label>
                        <input type="text"
                               name="departure"
                               id="departure"
                               class="form-control @error('departure') is-invalid @enderror"
                               placeholder="Например: Москва (SVO)"
                               value="{{ old('departure') }}"
                               required>
                        @error('departure')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Пункт прилета -->
                    <div class="col-md-6">
                        <label for="arrival" class="form-label">Пункт прилета *</label>
                        <input type="text"
                               name="arrival"
                               id="arrival"
                               class="form-control @error('arrival') is-invalid @enderror"
                               placeholder="Например: Санкт-Петербург (LED)"
                               value="{{ old('arrival') }}"
                               required>
                        @error('arrival')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Дата и время вылета -->
                    <div class="col-md-6">
                        <label for="departure_date" class="form-label">Дата и время вылета *</label>
                        <input type="datetime-local"
                               name="departure_date"
                               id="departure_date"
                               class="form-control @error('departure_date') is-invalid @enderror"
                               value="{{ old('departure_date') }}"
                               required>
                        @error('departure_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Дата и время прилета -->
                    <div class="col-md-6">
                        <label for="arrival_date" class="form-label">Дата и время прилета *</label>
                        <input type="datetime-local"
                               name="arrival_date"
                               id="arrival_date"
                               class="form-control @error('arrival_date') is-invalid @enderror"
                               value="{{ old('arrival_date') }}"
                               required>
                        @error('arrival_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Кнопки -->
                    <div class="col-12">
                        <div class="d-flex gap-2 pt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Создать рейс
                            </button>
                            <a href="{{ route('flight.active') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Отмена
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Подсказки -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Подсказки</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="mb-0">
                            <li>Номер рейса должен быть уникальным</li>
                            <li>Указывайте аэропорты в формате: "Город (Код аэропорта)"</li>
                            <li>Время полета указывается в часах</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="mb-0">
                            <li>Для новых рейсов рекомендуется выбирать статус "Запланирован"</li>
                            <li>Дата прилета должна быть позже даты вылета</li>
                            <li>Время полета рассчитывается автоматически</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .card {
            border: 1px solid #dee2e6;
        }
        .card-header {
            border-bottom: 1px solid #dee2e6;
        }
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Автоматический расчет времени полета при изменении дат
            const departureDateInput = document.getElementById('departure_date');
            const arrivalDateInput = document.getElementById('arrival_date');
            const flightTimeInput = document.getElementById('flight_time');

            function calculateFlightTime() {
                if (departureDateInput.value && arrivalDateInput.value) {
                    const departure = new Date(departureDateInput.value);
                    const arrival = new Date(arrivalDateInput.value);

                    if (arrival > departure) {
                        const diffMs = arrival - departure;
                        const diffHours = Math.round(diffMs / (1000 * 60 * 60));
                        flightTimeInput.value = diffHours > 0 ? diffHours : 1;
                    }
                }
            }

            departureDateInput.addEventListener('change', calculateFlightTime);
            arrivalDateInput.addEventListener('change', calculateFlightTime);

            // Установка минимальной даты для прилета
            departureDateInput.addEventListener('change', function() {
                arrivalDateInput.min = this.value;
            });
        });
    </script>
@endsection
