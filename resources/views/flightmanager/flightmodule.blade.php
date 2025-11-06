@extends('flightmanager.flightlayout')

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

        .main-content-wrapper {
            background: transparent !important;
        }

        .flight-chart-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            overflow: hidden;
        }

        .flight-chart-card .card-header {
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
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
                                <h4 class="card-title">Всего самолетов</h4>
                                <h2 class="mb-0">{{ $aircrafts->count() }}</h2>
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
                                <h4 class="card-title">В полете</h4>
                                <h2 class="mb-0">
                                    {{ $flights->where('flight_status_id', 3)->count() }}
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
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">Готовятся</h4>
                                <h2 class="mb-0">
                                    {{ $flights->where('flight_status_id', 1)->count() }}
                                </h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-plane-departure fa-2x"></i>
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
                                <h4 class="card-title">На тех. обслуживании</h4>
                                <h2 class="mb-0">
                                    {{ $aircrafts->where('aircraft_status_id', 3)->count() }}
                                </h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tools fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Графики -->
        <div class="row g-4">
            <!-- График полетов по самолетам -->
            <div class="col-lg-12">
                <div class="card flight-chart-card shadow-sm">
                    <div class="card-header bg-transparent text-white border-0">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>📊 Количество полетов по самолетам
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="flightsChart" height="350"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- График статусов самолетов -->
            <div class="col-lg-6">
                <div class="chart-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-bold text-dark mb-0">
                            <i class="fas fa-plane me-2 text-primary"></i>Статусы самолетов
                        </h5>
                    </div>
                    <div class="chart-container">
                        <canvas id="aircraftStatusChart"></canvas>
                    </div>
                    <div class="mt-4">
                        <div class="row text-center">
                            @php
                                $totalAircrafts = $aircrafts->count();
                                $inFlight = $aircrafts->where('aircraft_status_id', 1)->count();
                                $inAirport = $aircrafts->where('aircraft_status_id', 2)->count();
                                $inMaintenance = $aircrafts->where('aircraft_status_id', 3)->count();

                                $inFlightPercent = $totalAircrafts > 0 ? round(($inFlight / $totalAircrafts) * 100) : 0;
                                $inAirportPercent = $totalAircrafts > 0 ? round(($inAirport / $totalAircrafts) * 100) : 0;
                                $inMaintenancePercent = $totalAircrafts > 0 ? round(($inMaintenance / $totalAircrafts) * 100) : 0;
                            @endphp
                            <div class="col-4">
                                <div class="border-end">
                                    <h6 class="text-success mb-1">{{ $inFlight }} ({{ $inFlightPercent }}%)</h6>
                                    <small class="text-muted">В полете</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <h6 class="text-warning mb-1">{{ $inAirport }} ({{ $inAirportPercent }}%)</h6>
                                    <small class="text-muted">На стоянке</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h6 class="text-danger mb-1">{{ $inMaintenance }} ({{ $inMaintenancePercent }}%)</h6>
                                <small class="text-muted">Тех. обслуживание</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Дополнительная статистика -->
            <div class="col-lg-6">
                <div class="chart-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-bold text-dark mb-0">
                            <i class="fas fa-chart-line me-2 text-success"></i>Статистика полетов
                        </h5>
                    </div>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-primary mb-1">{{ $totalFlights ?? 0 }}</h4>
                                <small class="text-muted">Всего полетов</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-success mb-1">{{ $totalFlightHours ?? 0 }}</h4>
                                <small class="text-muted">Всего часов налета</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-info mb-1">{{ $activeFlights ?? 0 }}</h4>
                                <small class="text-muted">Активные полеты</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-warning mb-1">{{ $avgFlightHours ?? 0 }}</h4>
                                <small class="text-muted">Ср. время полета (ч)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Данные для графика полетов по самолетам
            const flightsData = @json($aircraftFlights);

            // График 1: Количество полетов по самолетам
            const flightsCtx = document.getElementById('flightsChart').getContext('2d');
            new Chart(flightsCtx, {
                type: 'bar',
                data: {
                    labels: flightsData.labels,
                    datasets: [{
                        label: 'Количество полетов',
                        data: flightsData.data,
                        backgroundColor: 'rgba(255, 255, 255, 0.7)',
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Общее количество полетов по каждому самолету',
                            color: 'white',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Количество полетов',
                                color: 'white'
                            },
                            ticks: {
                                color: 'white',
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Самолеты',
                                color: 'white'
                            },
                            ticks: {
                                color: 'white',
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        }
                    }
                }
            });

            // График 2: Статусы самолетов
            const inflight = {{ $aircrafts->where('aircraft_status_id', 1)->count() }};
            const inairport = {{ $aircrafts->where('aircraft_status_id', 2)->count() }};
            const maintenance = {{ $aircrafts->where('aircraft_status_id', 3)->count() }};

            const statusCtx = document.getElementById('aircraftStatusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['В полете', 'На стоянке', 'На тех. обслуживании'],
                    datasets: [{
                        data: [inflight, inairport, maintenance],
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
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        });
    </script>
@endsection
