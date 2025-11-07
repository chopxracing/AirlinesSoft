@extends('flightmanager.flightlayout')

@section('main_content')
    <div class="container-fluid p-4">
        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">✈️ Добавление нового самолета</h4>
            <a href="{{ route('flight.active') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Назад к списку
            </a>
        </div>

        <!-- Форма добавления самолета -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Основная информация</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('flight.aircraftcreate') }}" method="POST" class="row g-3">
                    @csrf

                    <!-- Название самолета -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Название самолета *</label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Например: Boeing 737-800"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Номер борта -->
                    <div class="col-md-6">
                        <label for="registration_number" class="form-label">Номер борта *</label>
                        <input type="text"
                               name="registration_number"
                               id="registration_number"
                               class="form-control @error('registration_number') is-invalid @enderror"
                               placeholder="Например: RA-73051"
                               value="{{ old('registration_number') }}"
                               required>
                        @error('registration_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Вместимость пассажиров -->
                    <div class="col-md-4">
                        <label for="passenger_capacity" class="form-label">Вместимость пассажиров *</label>
                        <input type="number"
                               name="passenger_capacity"
                               id="passenger_capacity"
                               class="form-control @error('passenger_capacity') is-invalid @enderror"
                               placeholder="Например: 189"
                               min="1"
                               max="1000"
                               value="{{ old('passenger_capacity') }}"
                               required>
                        @error('passenger_capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Дальность полета -->
                    <div class="col-md-4">
                        <label for="max_flight_kilometers" class="form-label">Дальность полета (км) *</label>
                        <input type="number"
                               name="max_flight_kilometers"
                               id="max_flight_kilometers"
                               class="form-control @error('max_flight_kilometers') is-invalid @enderror"
                               placeholder="Например: 5400"
                               min="100"
                               max="20000"
                               value="{{ old('max_flight_kilometers') }}"
                               required>
                        @error('max_flight_kilometers')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Налет часов -->
                    <div class="col-md-4">
                        <label for="flight_hours" class="form-label">Налет часов</label>
                        <input type="number"
                               name="flight_hours"
                               id="flight_hours"
                               class="form-control @error('flight_hours') is-invalid @enderror"
                               placeholder="Например: 1500"
                               min="0"
                               max="100000"
                               value="{{ old('flight_hours', 0) }}">
                        @error('flight_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Статус самолета -->
                    <div class="col-md-6">
                        <label for="aircraft_status_id" class="form-label">Статус самолета *</label>
                        <select name="aircraft_status_id"
                                id="aircraft_status_id"
                                class="form-select @error('aircraft_status_id') is-invalid @enderror"
                                required>
                            <option value="">Выберите статус</option>
                            @foreach($aircraftstatuses as $aircraftstatus)
                                <option value="{{ $aircraftstatus->id }}" {{ old('aircraft_status_id') == $aircraftstatus->id ? 'selected' : '' }}>
                                    {{ $aircraftstatus->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('aircraft_status_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Статус технического обслуживания -->
                    <div class="col-md-6">
                        <label for="maintenance_status_id" class="form-label">Статус ТО *</label>
                        <select name="maintenance_status_id"
                                id="maintenance_status_id"
                                class="form-select @error('maintenance_status_id') is-invalid @enderror"
                                required>
                            <option value="">Выберите статус ТО</option>
                            @foreach($maintenancestatuses as $maintenancestatus)
                                <option value="{{ $maintenancestatus->id }}" {{ old('maintenance_status_id') == $maintenancestatus->id ? 'selected' : '' }}>
                                    {{ $maintenancestatus->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('maintenance_status_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Кнопки -->
                    <div class="col-12">
                        <div class="d-flex gap-2 pt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plane me-2"></i>Зарегистрировать самолет
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-2"></i>Очистить форму
                            </button>
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
                            <li>Название должно включать модель самолета</li>
                            <li>Номер борта должен быть уникальным</li>
                            <li>Вместимость указывается в количестве пассажиров</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="mb-0">
                            <li>Дальность полета указывается в километрах</li>
                            <li>Для новых самолетов налет часов можно указать 0</li>
                            <li>Статус "Активный" для готовых к полетам самолетов</li>
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
        .btn {
            min-width: 200px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Автозаполнение названия при вводе номера борта
            const nameInput = document.getElementById('name');
            const registrationInput = document.getElementById('registration_number');

            registrationInput.addEventListener('blur', function() {
                if (!nameInput.value && registrationInput.value) {
                    // Можно добавить логику автозаполнения названия по номеру борта
                    nameInput.value = 'Самолет ' + registrationInput.value;
                }
            });

            // Валидация формы
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const passengerCapacity = document.getElementById('passenger_capacity').value;
                const maxFlightKm = document.getElementById('max_flight_kilometers').value;

                if (passengerCapacity < 1) {
                    alert('Вместимость пассажиров должна быть не менее 1');
                    e.preventDefault();
                }

                if (maxFlightKm < 100) {
                    alert('Дальность полета должна быть не менее 100 км');
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
