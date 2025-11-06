@extends('crewmodule.crewlayout')

@section('main_content')
    <div class="container-fluid p-4">
        <!-- Фильтры -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Фильтры сотрудников</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('crew.employee') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="name" class="form-label">Имя</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Введите имя" value="{{ request('name') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="surname" class="form-label">Фамилия</label>
                        <input type="text" name="surname" id="surname" class="form-control" placeholder="Введите фамилию" value="{{ request('surname') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="username" class="form-label">Имя пользователя</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Введите имя пользователя" value="{{ request('username') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="status_id" class="form-label">Статус сотрудника</label>
                        <select name="status_id" id="status_id" class="form-select">
                            <option value="">Все статусы</option>
                            @foreach($crewStatuses as $status)
                                <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="position_id" class="form-label">Позиция сотрудника</label>
                        <select name="position_id" id="position_id" class="form-select">
                            <option value="">Все позиции</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" {{ request('position_id') == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="clearance_id" class="form-label">Допуск</label>
                        <select name="clearance_id" id="clearance_id" class="form-select">
                            <option value="">Все допуски</option>
                            @foreach($clearances as $clearance)
                                <option value="{{ $clearance->id }}" {{ request('clearance_id') == $clearance->id ? 'selected' : '' }}>
                                    {{ $clearance->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Применить фильтры
                            </button>
                            <a href="{{ route('crew.employee') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Сбросить
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

                <!-- Результаты -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Список сотрудников</h5>
                        <span class="badge bg-primary">Всего: {{ $crews->count() }}</span>
                    </div>
                    <div class="card-body">
                        @if($crews->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                    <tr>
                                        <th>ФИО</th>
                                        <th>Имя пользователя</th>
                                        <th>Должность</th>
                                        <th>Налет (за 24 ч)</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($crews as $crew)
                                        @php
                                            $employeeData = [
                                                'id' => $crew->id,
                                                'surname' => $crew->surname,
                                                'name' => $crew->name,
                                                'username' => $crew->username,
                                                'email' => $crew->email,
                                                'phone' => $crew->phone,
                                                'time_in_air' => $crew->time_in_air,
                                                'time_out_air' => $crew->time_out_air,
                                                'medicial_to' => $crew->medicial_to,
                                                'license_to' => $crew->license_to,
                                                'medicial_number' => $crew->medicial_number,
                                                'license_number' => $crew->license_number,
                                            ];

                                            // Добавляем отношения, если они существуют
                                            if ($crew->position) {
                                                $employeeData['position'] = ['name' => $crew->position->name];
                                            }
                                            if ($crew->crewstatus) {
                                                $employeeData['crewstatus'] = [
                                                    'name' => $crew->crewstatus->name,
                                                    'color' => $crew->crewstatus->color ?? 'bg-secondary'
                                                ];
                                            }
                                            if ($crew->clearance) {
                                                $employeeData['clearance'] = ['name' => $crew->clearance->name];
                                            }
                                        @endphp

                                        <tr>
                                            <td>
                                                <strong>{{ $crew->surname }} {{ $crew->name }}</strong>
                                            </td>
                                            <td>{{ $crew->username }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $crew->position->name ?? 'Не указано' }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <div class="fw-bold">{{ $crew->flightHistories->sum('flight_hours') ?? 0 }}</div>
                                                    <small class="text-muted">в воздухе</small>
                                                </div>
                                            </td>
                                            <td>
                                            <span class="badge {{ $crew->crewstatus->color ?? 'bg-secondary' }}">
                                                {{ $crew->crewstatus->name ?? 'Не указан' }}
                                            </span>
                                            </td>
                                            <td>
                                                <div class="btn-group-vertical btn-group-sm">
                                                    <button class="btn btn-outline-primary view-employee-btn"
                                                            title="Просмотр"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#employeeModal"
                                                            data-employee='@json($employeeData)'>
                                                        <i class="fas fa-eye"></i>
                                                    </button>


                                                    <form action="{{ route('crew.destroy', $crew->id) }}" method="post" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-outline-danger" title="Удалить" onclick="return confirm('Вы уверены?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Пагинация -->
                            @if($crews->hasPages())
                                <div class="mt-4">
                                    {{ $crews->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Сотрудники не найдены</h5>
                                <p class="text-muted">Попробуйте изменить параметры фильтрации</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Модальное окно просмотра сотрудника -->
            <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="employeeModalLabel">Информация о сотруднике</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- Основная информация -->
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Основная информация</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>ID:</strong> <span id="modal-id">-</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Фамилия:</strong> <span id="modal-surname">-</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Имя:</strong> <span id="modal-name">-</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Имя пользователя:</strong> <span id="modal-username">-</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Email:</strong> <span id="modal-email">-</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Телефон:</strong> <span id="modal-phone">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Служебная информация -->
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Служебная информация</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>Должность:</strong> <span id="modal-position" class="badge bg-info">-</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Статус:</strong> <span id="modal-status" class="badge bg-secondary">-</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Допуск:</strong> <span id="modal-clearance" class="badge bg-warning">-</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Налет:</strong> <span id="modal-time-in-air">0</span> часов
                                            </div>
                                            <div class="mb-2">
                                                <strong>Вне полета:</strong> <span id="modal-time-out-air">0</span> часов
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Документы -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Документы и сроки действия</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <strong>Медицинская справка:</strong>
                                                        <span id="modal-medical-number">-</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Дата истечения:</strong>
                                                        <span id="modal-medical-to">-</span>
                                                        <span id="modal-medical-badge" class="badge ms-2">-</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <strong>Лицензия:</strong>
                                                        <span id="modal-license-number">-</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Дата истечения:</strong>
                                                        <span id="modal-license-to">-</span>
                                                        <span id="modal-license-badge" class="badge ms-2">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Клик по кнопке просмотра
        document.querySelectorAll('.view-employee-btn').forEach(button => {
            button.addEventListener('click', function() {
                const raw = this.dataset.employee;
                console.log('RAW employee data:', raw);

                let data = raw;
                if (typeof raw === 'string') {
                    try {
                        data = JSON.parse(raw);
                    } catch (e) {
                        console.warn('Похоже, это уже объект, парсить не нужно');
                    }
                }

                fillModalData(data);
            });
        });


        function fillModalData(data) {
            // Основная информация
            document.getElementById('modal-id').textContent = data.id || '-';
            document.getElementById('modal-surname').textContent = data.surname || '-';
            document.getElementById('modal-name').textContent = data.name || '-';
            document.getElementById('modal-username').textContent = data.username || '-';
            document.getElementById('modal-email').textContent = data.email || '-';
            document.getElementById('modal-phone').textContent = data.phone || '-';

            // Служебная информация
            document.getElementById('modal-position').textContent = data.position?.name || '-';
            document.getElementById('modal-status').textContent = data.crewstatus?.name || '-';
            document.getElementById('modal-status').className = 'badge ' + (data.crewstatus?.color || 'bg-secondary');
            document.getElementById('modal-clearance').textContent = data.clearance?.name || '-';

            // Налет и вне полета
            document.getElementById('modal-time-in-air').textContent = data.time_in_air || 0;
            document.getElementById('modal-time-out-air').textContent = data.time_out_air || 0;

            // Документы
            document.getElementById('modal-medical-number').textContent = data.medicial_number || '-';
            document.getElementById('modal-license-number').textContent = data.license_number || '-';

            const medicalBadge = document.getElementById('modal-medical-badge');
            const medicalTo = document.getElementById('modal-medical-to');
            const licenseBadge = document.getElementById('modal-license-badge');
            const licenseTo = document.getElementById('modal-license-to');

            // Медицинская справка
            if (data.medicial_to) {
                const date = new Date(data.medicial_to);
                const today = new Date();
                const diffDays = Math.ceil((date - today) / (1000 * 60 * 60 * 24));

                if (diffDays < 0) {
                    medicalBadge.textContent = 'Просрочена';
                    medicalBadge.className = 'badge bg-danger';
                } else if (diffDays <= 30) {
                    medicalBadge.textContent = 'Скоро истекает';
                    medicalBadge.className = 'badge bg-warning';
                } else {
                    medicalBadge.textContent = 'Действительна';
                    medicalBadge.className = 'badge bg-success';
                }
                medicalTo.textContent = date.toLocaleDateString('ru-RU');
            } else {
                medicalBadge.textContent = 'Не указана';
                medicalBadge.className = 'badge bg-secondary';
                medicalTo.textContent = '-';
            }

            // Лицензия
            if (data.license_to) {
                const date = new Date(data.license_to);
                const today = new Date();
                const diffDays = Math.ceil((date - today) / (1000 * 60 * 60 * 24));

                if (diffDays < 0) {
                    licenseBadge.textContent = 'Просрочена';
                    licenseBadge.className = 'badge bg-danger';
                } else if (diffDays <= 30) {
                    licenseBadge.textContent = 'Скоро истекает';
                    licenseBadge.className = 'badge bg-warning';
                } else {
                    licenseBadge.textContent = 'Действительна';
                    licenseBadge.className = 'badge bg-success';
                }
                licenseTo.textContent = date.toLocaleDateString('ru-RU');
            } else {
                licenseBadge.textContent = 'Не указана';
                licenseBadge.className = 'badge bg-secondary';
                licenseTo.textContent = '-';
            }
        }

    });
</script>


@push('styles')
            <style>
                .modal-body .card {
                    border: 1px solid #dee2e6;
                    border-radius: 0.375rem;
                }

                .modal-body .card-header {
                    background-color: #f8f9fa;
                    border-bottom: 1px solid #dee2e6;
                    padding: 0.75rem 1rem;
                }

                .modal-body .card-body {
                    padding: 1rem;
                }

                .badge {
                    font-size: 0.75em;
                }

                .view-employee-btn.active {
                    background-color: #0d6efd;
                    border-color: #0d6efd;
                    color: white;
                }

                .btn-group-vertical .btn {
                    margin-bottom: 0.25rem;
                }
            </style>
    @endpush
