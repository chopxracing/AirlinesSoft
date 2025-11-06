@extends('crewmodule.crewlayout')

@section('main_content')
    <div class="container-fluid p-4">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-id-card me-2"></i>
                    Добавить лицензию для сотрудника
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('crew.updatelicense') }}" class="row g-3">
                    @csrf

                    <!-- Выбор сотрудника -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Информация о сотруднике</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="user_id" class="form-label">Сотрудник <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Выберите сотрудника</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->surname }} {{ $user->name }} ({{ $user->username }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Информация о лицензии -->
                    <div class="col-12 mt-4">
                        <h6 class="border-bottom pb-2 mb-3">Информация о лицензии</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="license_number" class="form-label">Номер лицензии <span class="text-danger">*</span></label>
                        <input type="text" name="license_number" id="license_number" class="form-control"
                               placeholder="Введите номер лицензии"
                               value="{{ old('license_number') }}" required>
                        @error('license_number')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="license_to" class="form-label">Дата окончания действия <span class="text-danger">*</span></label>
                        <input type="date" name="license_to" id="license_to" class="form-control"
                               value="{{ old('license_to') }}" required>
                        @error('license_to')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>




                    <!-- Статус лицензии -->
                    <div class="col-md-6">
                        <label class="form-label">Текущий статус</label>
                        <div class="mt-2">
                            <span id="license-status" class="badge bg-secondary">Не выбрана</span>
                            <small class="text-muted d-block mt-1" id="days-remaining"></small>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="col-12 mt-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Сохранить лицензию
                            </button>
                            <a href="{{ route('crew.employee') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Отмена
                            </a>
                            <button type="button" class="btn btn-outline-info" onclick="clearForm()">
                                <i class="fas fa-broom me-2"></i>Очистить форму
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Список недавно добавленных лицензий -->
        @if(isset($recentLicenses) && $recentLicenses->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Недавно добавленные лицензии
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                            <tr>
                                <th>Сотрудник</th>
                                <th>Номер лицензии</th>
                                <th>Тип</th>
                                <th>Действует до</th>
                                <th>Статус</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recentLicenses as $license)
                                <tr>
                                    <td>{{ $license->surname }} {{ $license->name }}</td>
                                    <td><code>{{ $license->license_number }}</code></td>
                                    <td>
                                        <span class="badge bg-info">{{ $license->license_type ?? 'Не указан' }}</span>
                                    </td>
                                    <td>{{ $license->license_to }}</td>
                                    <td>
                                        @php
                                            $expiryDate = \Carbon\Carbon::parse($license->license_to);
                                            $today = \Carbon\Carbon::now();
                                            $daysLeft = $today->diffInDays($expiryDate, false);
                                        @endphp
                                        @if($daysLeft < 0)
                                            <span class="badge bg-danger">Просрочена</span>
                                        @elseif($daysLeft <= 30)
                                            <span class="badge bg-warning">Скоро истекает ({{ $daysLeft }} д.)</span>
                                        @else
                                            <span class="badge bg-success">Действительна</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .card-header h5 {
            font-weight: 600;
        }

        .border-bottom {
            border-color: #dee2e6 !important;
        }

        .btn-success {
            min-width: 180px;
        }

        code {
            background-color: #f8f9fa;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-family: monospace;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userSelect = document.getElementById('user_id');
            const positionField = document.getElementById('position');
            const licenseToInput = document.getElementById('license_to');
            const licenseStatus = document.getElementById('license-status');
            const daysRemaining = document.getElementById('days-remaining');

            // Установка минимальной даты - сегодня
            const today = new Date().toISOString().split('T')[0];
            licenseToInput.min = today;

            // Автозаполнение информации о сотруднике
            if (userSelect) {
                userSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value) {
                        // Здесь можно добавить AJAX запрос для получения детальной информации о сотруднике
                        // Пока просто очищаем поле должности
                        positionField.value = 'Информация загружается...';

                        // Имитация загрузки данных
                        setTimeout(() => {
                            positionField.value = 'Пилот'; // Заглушка
                        }, 500);
                    } else {
                        positionField.value = '';
                    }
                });
            }

            // Проверка срока действия лицензии
            if (licenseToInput) {
                licenseToInput.addEventListener('change', function() {
                    updateLicenseStatus(this.value);
                });
            }

            // Автозаполнение формата для номера лицензии
            const licenseNumberInput = document.getElementById('license_number');
            if (licenseNumberInput) {
                licenseNumberInput.addEventListener('input', function(e) {
                    // Приведение к верхнему регистру и удаление лишних символов
                    e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
                });
            }

            function updateLicenseStatus(expiryDate) {
                if (!expiryDate) {
                    licenseStatus.textContent = 'Не выбрана';
                    licenseStatus.className = 'badge bg-secondary';
                    daysRemaining.textContent = '';
                    return;
                }

                const expiry = new Date(expiryDate);
                const today = new Date();
                const timeDiff = expiry.getTime() - today.getTime();
                const daysLeft = Math.ceil(timeDiff / (1000 * 3600 * 24));

                if (daysLeft < 0) {
                    licenseStatus.textContent = 'Просрочена';
                    licenseStatus.className = 'badge bg-danger';
                    daysRemaining.textContent = `Просрочена ${Math.abs(daysLeft)} дней назад`;
                } else if (daysLeft <= 30) {
                    licenseStatus.textContent = 'Скоро истекает';
                    licenseStatus.className = 'badge bg-warning';
                    daysRemaining.textContent = `Осталось ${daysLeft} дней`;
                } else {
                    licenseStatus.textContent = 'Действительна';
                    licenseStatus.className = 'badge bg-success';
                    daysRemaining.textContent = `Осталось ${daysLeft} дней`;
                }
            }

            // Инициализация статуса при загрузке
            if (licenseToInput.value) {
                updateLicenseStatus(licenseToInput.value);
            }
        });

        function clearForm() {
            if (confirm('Вы уверены, что хотите очистить форму?')) {
                document.querySelector('form').reset();
                document.getElementById('position').value = '';
                document.getElementById('license-status').textContent = 'Не выбрана';
                document.getElementById('license-status').className = 'badge bg-secondary';
                document.getElementById('days-remaining').textContent = '';
            }
        }
    </script>
@endpush
