@extends('crewmodule.crewlayout')

@section('main_content')
    <div class="container-fluid p-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Добавить нового сотрудника
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('crew.createemployee') }}" class="row g-3">
                    @csrf

                    <!-- Личная информация -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Личная информация</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label">Имя <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Введите имя" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="surname" class="form-label">Фамилия <span class="text-danger">*</span></label>
                        <input type="text" name="surname" id="surname" class="form-control" placeholder="Введите фамилию" value="{{ old('surname') }}" required>
                        @error('surname')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Учетные данные -->
                    <div class="col-12 mt-4">
                        <h6 class="border-bottom pb-2 mb-3">Учетные данные</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="username" class="form-label">Логин <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Введите логин" value="{{ old('username') }}" required>
                        @error('username')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Пароль <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Введите пароль" required>
                        @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="example@company.com" value="{{ old('email') }}" required>
                        @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Номер телефона</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="+7 (XXX) XXX-XX-XX" value="{{ old('phone') }}">
                        @error('phone')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Паспортные данные -->
                    <div class="col-12 mt-4">
                        <h6 class="border-bottom pb-2 mb-3">Паспортные данные</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="idoc_series" class="form-label">Серия паспорта</label>
                        <input type="text" name="idoc_series" id="idoc_series" class="form-control" placeholder="XXXX" value="{{ old('idoc_series') }}" maxlength="4">
                        @error('idoc_series')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="idoc_number" class="form-label">Номер паспорта</label>
                        <input type="text" name="idoc_number" id="idoc_number" class="form-control" placeholder="XXXXXX" value="{{ old('idoc_number') }}" maxlength="6">
                        @error('idoc_number')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Служебная информация -->
                    <div class="col-12 mt-4">
                        <h6 class="border-bottom pb-2 mb-3">Служебная информация</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="position_id" class="form-label">Должность <span class="text-danger">*</span></label>
                        <select name="position_id" id="position_id" class="form-select" required>
                            <option value="">Выберите должность</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_id')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="clearance_id" class="form-label">Допуск <span class="text-danger">*</span></label>
                        <select name="clearance_id" id="clearance_id" class="form-select" required>
                            <option value="">Выберите уровень допуска</option>
                            @foreach($clearances as $clearance)
                                <option value="{{ $clearance->id }}" {{ old('clearance_id') == $clearance->id ? 'selected' : '' }}>
                                    {{ $clearance->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('clearance_id')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Кнопки -->
                    <div class="col-12 mt-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Создать сотрудника
                            </button>
                            <a href="{{ route('crew.employee') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Отмена
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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
            // Маска для телефона
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.startsWith('7') || value.startsWith('8')) {
                        value = value.substring(1);
                    }
                    if (value.length > 0) {
                        value = '+7 (' + value;
                        if (value.length > 7) {
                            value = value.substring(0, 7) + ') ' + value.substring(7);
                        }
                        if (value.length > 12) {
                            value = value.substring(0, 12) + '-' + value.substring(12);
                        }
                        if (value.length > 15) {
                            value = value.substring(0, 15) + '-' + value.substring(15);
                        }
                    }
                    e.target.value = value;
                });
            }

            // Ограничение для серии паспорта (4 цифры)
            const seriesInput = document.getElementById('idoc_series');
            if (seriesInput) {
                seriesInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
                });
            }

            // Ограничение для номера паспорта (6 цифр)
            const numberInput = document.getElementById('idoc_number');
            if (numberInput) {
                numberInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '').substring(0, 6);
                });
            }
        });
    </script>
@endpush
