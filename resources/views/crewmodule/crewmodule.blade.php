@extends('crewmodule.crewlayout')

@section('main_content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    <style>
        .dashboard-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            transition: transform 0.3s ease;
            height: 100%;
        }

        .chart-card:hover {
            transform: translateY(-5px);
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin: 0 auto;
        }

        .stats-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            margin-right: 10px;
        }

        /* Убираем градиентный фон для body, так как он уже есть в layout */
        .main-content-wrapper {
            background: transparent !important;
        }
    </style>

    <div class="container-fluid p-4 main-content-wrapper">
        <!-- Существующие карточки статистики -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">Всего сотрудников</h4>
                                <h2 class="mb-0">{{ $crews->count() }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
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
                                <h4 class="card-title">КВС</h4>
                                <h2 class="mb-0">
                                    {{ $crews->where('position_id', 1)->count() }}
                                </h2>
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
                                <h4 class="card-title">Вторые пилоты</h4>
                                <h2 class="mb-0">
                                    {{ $crews->where('position_id', 2)->count() }}
                                </h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-plane fa-2x"></i>
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
                                <h4 class="card-title">Бортпроводники</h4>
                                <h2 class="mb-0">
                                    {{ $crews->where('position_id', 3)->count() }}
                                </h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-concierge-bell fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Графики -->
        <div class="row g-4">
            <!-- График 1: Распределение персонала -->
            <div class="col-lg-6">
                <div class="chart-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-bold text-dark mb-0">
                            <i class="fas fa-users me-2 text-primary"></i>Распределение персонала
                        </h5>
                        <span class="stats-badge bg-primary text-white">Всего: {{ $crews->count() }}</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="personnelChart"></canvas>
                    </div>
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-6">
                                <div class="legend-item">
                                    <div class="legend-color bg-primary"></div>
                                    <small>КВС ({{ round($crews->where('position_id', 1)->count() / $crews->count() * 100) }}%)</small>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color bg-success"></div>
                                    <small>Вторые пилоты ({{ round($crews->where('position_id', 2)->count() / $crews->count() * 100) }}%)</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="legend-item">
                                    <div class="legend-color bg-info"></div>
                                    <small>Бортпроводники ({{ round($crews->where('position_id', 3)->count() / $crews->count() * 100) }}%)</small>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color bg-warning"></div>
                                    <small>Остальные ({{ round(($crews->count() - $crews->whereIn('position_id', [1,2,3])->count()) / $crews->count() * 100) }}%)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- График 2: Статус документов -->
            <div class="col-lg-6">
                <div class="chart-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-bold text-dark mb-0">
                            <i class="fas fa-file-contract me-2 text-success"></i>Статусы сотрудников
                        </h5>
                        <span class="stats-badge bg-success text-white">Активные: ({{ round($crews->where('status_id', 1)->count() / $crews->count() * 100) }}%)</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="documentsChart"></canvas>
                    </div>
                    <div class="mt-4">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border-end">
                                    <h6 class="text-success mb-1">({{ round($crews->where('status_id', 1)->count() / $crews->count() * 100) }}%)</h6>
                                    <small class="text-muted">Отдых: ({{ round($crews->where('status_id', 1)->count() / $crews->count() * 100) }}%) </small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <h6 class="text-warning mb-1">({{ round($crews->where('status_id', 2)->count() / $crews->count() * 100) }}%)</h6>
                                    <small class="text-muted">В полете: ({{ round($crews->where('status_id', 2)->count() / $crews->count() * 100) }}%)</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h6 class="text-danger mb-1">({{ round($crews->where('status_id', 3)->count() / $crews->count() * 100) }}%)</h6>
                                <small class="text-muted">Готовятся: ({{ round($crews->where('status_id', 3)->count() / $crews->count() * 100) }}%)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Карточка срочного внимания -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Срочное внимание
                </h5>
            </div>
            <div class="card-body">
                @foreach($crews as $crew)
                    @php
                        $today = now();
                        $medicalExpiry = $crew->medicial_to ? \Carbon\Carbon::parse($crew->medicial_to) : null;
                        $licenseExpiry = $crew->license_to ? \Carbon\Carbon::parse($crew->license_to) : null;

                        $medicalWarning = $medicalExpiry && $medicalExpiry->diffInDays($today) <= 30;
                        $licenseWarning = $licenseExpiry && $licenseExpiry->diffInDays($today) <= 30;
                    @endphp

                    @if($medicalWarning || $licenseWarning)
                        <div class="alert alert-warning d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $crew->username }} - {{$crew->Position->name}}. id - {{ $crew->id }}</strong>
                                @if($medicalWarning)
                                    <br>Медицинская справка истекает: {{ $crew->medicial_to }}
                                    (осталось {{ $medicalExpiry->diffInDays($today) }} дней)
                                @endif
                                @if($licenseWarning)
                                    <br>Лицензия истекает: {{ $crew->license_to }}
                                    (осталось {{ $licenseExpiry->diffInDays($today) }} дней)
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach

                @if(!$crews->contains(function($crew) use ($today) {
                    $medicalExpiry = $crew->medicial_to ? \Carbon\Carbon::parse($crew->medicial_to) : null;
                    $licenseExpiry = $crew->license_to ? \Carbon\Carbon::parse($crew->license_to) : null;
                    return ($medicalExpiry && $medicalExpiry->diffInDays($today) <= 30) ||
                           ($licenseExpiry && $licenseExpiry->diffInDays($today) <= 30);
                }))
                    <div class="text-center text-muted">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <p>Все документы в порядке</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Данные для графиков на основе реальных данных
            const totalCrews = {{ $crews->count() }};
            const captains = {{ $crews->where('position_id', 1)->count() }};
            const secondPilots = {{ $crews->where('position_id', 2)->count() }};
            const attendants = {{ $crews->where('position_id', 3)->count() }};
            const others = totalCrews - captains - secondPilots - attendants;

            // График 1: Распределение персонала
            const personnelCtx = document.getElementById('personnelChart').getContext('2d');
            new Chart(personnelCtx, {
                type: 'doughnut',
                data: {
                    labels: ['КВС', 'Вторые пилоты', 'Бортпроводники', 'Остальные'],
                    datasets: [{
                        data: [captains, secondPilots, attendants, others],
                        backgroundColor: [
                            '#0d6efd',
                            '#198754',
                            '#0dcaf0',
                            '#ffc107'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const percentage = ((context.parsed / totalCrews) * 100).toFixed(1);
                                    return `${context.label}: ${context.parsed} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            const rest = {{ $crews->where('status_id', 1)->count() }};
            const onboard = {{ $crews->where('status_id', 2)->count() }};
            const preparation = {{ $crews->where('status_id', 3)->count() }};

            // График 2: Статус документов (примерные данные)
            const documentsCtx = document.getElementById('documentsChart').getContext('2d');
            new Chart(documentsCtx, {
                type: 'pie',
                data: {
                    labels: ['Отдых', 'В полете', 'Готовятся'],
                    datasets: [{
                        data: [rest, onboard, preparation],
                        backgroundColor: [
                            '#198754',
                            '#ffc107',
                            '#dc3545'
                        ],
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endsection
