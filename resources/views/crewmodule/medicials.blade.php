@extends('crewmodule.crewlayout')

@section('main_content')
    <div class="container-fluid p-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-file-medical me-2"></i>
                    Добавить медицинскую справку для сотрудника
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('crew.updatemedicials') }}" class="row g-3">
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

                    <!-- Информация о справке -->
                    <div class="col-12 mt-4">
                        <h6 class="border-bottom pb-2 mb-3">Информация о медицинской справке</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="medicial_number" class="form-label">Номер справки <span class="text-danger">*</span></label>
                        <input type="text" name="medicial_number" id="medicial_number" class="form-control"
                               placeholder="Введите номер медицинской справки"
                               value="{{ old('medicial_number') }}" required>
                        @error('medicial_number')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="medicial_to" class="form-label">Дата окончания действия <span class="text-danger">*</span></label>
                        <input type="date" name="medicial_to" id="medicial_to" class="form-control"
                               value="{{ old('medicial_to') }}" required>
                        @error('medicial_to')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Кнопки -->
                    <div class="col-12 mt-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Сохранить справку
                            </button>
                            <a href="{{ route('crew.employee') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Отмена
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Список недавно добавленных справок -->
        @if(isset($recentMedicials) && $recentMedicials->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Недавно добавленные справки</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                            <tr>
                                <th>Сотрудник</th>
                                <th>Номер справки</th>
                                <th>Действует до</th>
                                <th>Статус</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recentMedicials as $medicial)
                                <tr>
                                    <td>{{ $medicial->surname }} {{ $medicial->name }}</td>
                                    <td>{{ $medicial->medicial_number }}</td>
                                    <td>{{ $medicial->medicial_to }}</td>
                                    <td>
                                        @php
                                            $expiryDate = \Carbon\Carbon::parse($medicial->medicial_to);
                                            $today = \Carbon\Carbon::now();
                                            $daysLeft = $today->diffInDays($expiryDate, false);
                                        @endphp
                                        @if($daysLeft < 0)
                                            <span class="badge bg-danger">Просрочена</span>
                                        @elseif($daysLeft <= 30)
                                            <span class="badge bg-warning">Скоро истекает</span>
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
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Установка минимальной даты - сегодня
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('medicial_to').min = today;

            // Автозаполнение формата для номера справки
            const medicialNumberInput = document.getElementById('medicial_number');
            if (medicialNumberInput) {
                medicialNumberInput.addEventListener('input', function(e) {
                    // Можно добавить маску для номера справки если нужно
                    // Например: только буквы и цифры
                    e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
                });
            }

            // Подсказка при фокусе на поле номера справки
            medicialNumberInput.addEventListener('focus', function() {
                this.setAttribute('title', 'Формат: буквы и цифры, можно использовать дефис');
            });
        });
    </script>
@endpush
