@extends('layout')

@section('main_content')
        <div class="container-fluid py-4">
            <!-- Заголовок и общая статистика -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">🏠 Авиационная панель управления</h4>
                            <p class="text-muted mb-0">Обзор деятельности авиакомпании</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary fs-6">{{ now()->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Основная статистика -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">{{ $totalAircrafts ?? 0 }}</h4>
                                    <p class="mb-0">Самолетов в парке</p>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-plane fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">{{ $totalFlights ?? 0 }}</h4>
                                    <p class="mb-0">Всего рейсов</p>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-route fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">{{ $totalUsers ?? 0 }}</h4>
                                    <p class="mb-0">Сотрудников</p>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-users fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">{{ $activeFlights ?? 0 }}</h4>
                                    <p class="mb-0">Активных рейсов</p>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-play-circle fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Левая колонка -->
                <div class="col-xl-8">
                    <!-- Статусы самолетов -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Статусы самолетов</h6>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <canvas id="aircraftStatusChart" height="200"></canvas>
                                </div>
                                <div class="col-md-6">
                                    @php
                                        $statusColors = [
                                            'Доступен' => 'bg-success',
                                            'В полете' => 'bg-primary',
                                            'Обслуживание' => 'bg-warning',
                                            'Ремонт' => 'bg-danger'
                                        ];
                                    @endphp
                                    @foreach($aircraftStatuses ?? [] as $status)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small">{{ $status->name }}</span>
                                            <div class="d-flex align-items-center">
                                    <span class="badge {{ $statusColors[$status->name] ?? 'bg-secondary' }} me-2">
                                        {{ $status->count ?? 0 }}
                                    </span>
                                                <small class="text-muted">{{ $status->percentage ?? 0 }}%</small>
                                            </div>
                                        </div>
                                        <div class="progress mb-3" style="height: 6px;">
                                            <div class="progress-bar {{ $statusColors[$status->name] ?? 'bg-secondary' }}"
                                                 style="width: {{ $status->percentage ?? 0 }}%"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Последние рейсы -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-plane-departure me-2 text-success"></i>Последние рейсы</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Рейс</th>
                                        <th>Маршрут</th>
                                        <th>Самолет</th>
                                        <th>Вылет</th>
                                        <th>Статус</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recentFlights ?? [] as $flight)
                                        <tr>
                                            <td>
                                                <strong>#{{ $flight->flight_number }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $flight->departure }}</small>
                                                <i class="fas fa-arrow-right mx-2 text-muted small"></i>
                                                <small class="text-muted">{{ $flight->arrival }}</small>
                                            </td>
                                            <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $flight->aircraft->name ?? 'N/A' }}
                                        </span>
                                            </td>
                                            <td>
                                                <small>{{ $flight->departure_date->format('d.m H:i') }}</small>
                                            </td>
                                            <td>
                                        <span class="badge
                                            @if($flight->flight_status_id == 1) bg-secondary
                                            @elseif($flight->flight_status_id == 2) bg-info
                                            @elseif($flight->flight_status_id == 3) bg-success
                                            @elseif($flight->flight_status_id == 4) bg-warning
                                            @else bg-danger @endif">
                                            {{ $flight->flightStatus->name ?? 'N/A' }}
                                        </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Правая колонка -->
                <div class="col-xl-4">
                    <!-- Статистика персонала -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-user-tie me-2 text-warning"></i>Персонал по должностям</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="positionsChart" height="250"></canvas>
                        </div>
                    </div>

                    <!-- Системная информация -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-info-circle me-2 text-secondary"></i>Системная информация</h6>
                        </div>
                        <div class="card-body">
                            <div class="small">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Всего налет часов:</span>
                                    <strong>{{ $totalFlightHours ?? 0 }}ч</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Самолетов в обслуживании:</span>
                                    <strong>{{ $maintenanceAircrafts ?? 0 }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Пилоты с допуском:</span>
                                    <strong>{{ $clearedPilots ?? 0 }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Рейсов сегодня:</span>
                                    <strong>{{ $todayFlights ?? 0 }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .stat-card {
                border: none;
                border-radius: 12px;
                transition: transform 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-3px);
            }

            .stat-icon {
                opacity: 0.8;
            }

            .card {
                border: 1px solid #e3f2fd;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            }

            .card-header {
                background-color: #f8f9fa;
                border-bottom: 1px solid #e3f2fd;
                border-radius: 12px 12px 0 0 !important;
                padding: 1rem 1.25rem;
            }

            .table th {
                border-top: none;
                font-weight: 600;
                font-size: 0.85rem;
                text-transform: uppercase;
                color: #6c757d;
            }

            .btn {
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn:hover {
                transform: translateY(-1px);
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // График статусов самолетов
                const aircraftCtx = document.getElementById('aircraftStatusChart').getContext('2d');
                new Chart(aircraftCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(collect($aircraftStatuses ?? [])->pluck('name')) !!},
                        datasets: [{
                            data: {!! json_encode(collect($aircraftStatuses ?? [])->pluck('count')) !!},
                            backgroundColor: [
                                '#28a745', '#007bff', '#ffc107', '#dc3545', '#6c757d'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });

                // График должностей
                const positionsCtx = document.getElementById('positionsChart').getContext('2d');
                new Chart(positionsCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(collect($positionStats ?? [])->pluck('name')) !!},
                        datasets: [{
                            label: 'Количество сотрудников',
                            data: {!! json_encode(collect($positionStats ?? [])->pluck('count')) !!},
                            backgroundColor: '#ffc107',
                            borderColor: '#e0a800',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            });
        </script>
@endsection
