@extends('work.worklayout')

@section('main_content')
    <div class="container-fluid p-4">
        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">📋 Мои полеты</h4>
            <span class="badge bg-primary fs-6">Всего: {{ $flighthistories->count() }}</span>
        </div>

        @if($flighthistories->count() > 0)
            <div class="row">
                @foreach($flighthistories as $flighthistory)
                    @if($flighthistory->flight && !in_array($flighthistory->flight->flight_status_id, [4, 5]))
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Рейс #{{ $flighthistory->flight->flight_number }}</h6>
                                <span class="badge bg-info">{{ $flighthistory->flight->aircraft->name ?? 'N/A' }}</span>
                            </div>
                            <div class="card-body">
                                <!-- Маршрут -->
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="text-center">
                                            <strong class="d-block">{{ $flighthistory->flight->departure }}</strong>
                                            <small class="text-muted">Вылет</small>
                                        </div>
                                        <div class="mx-3">
                                            <i class="fas fa-arrow-right text-muted"></i>
                                        </div>
                                        <div class="text-center">
                                            <strong class="d-block">{{ $flighthistory->flight->arrival }}</strong>
                                            <small class="text-muted">Прилет</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Время полета -->
                                <div class="mb-3 p-3 bg-light rounded">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Вылет</small>
                                            <strong>{{ $flighthistory->flight->departure_date->format('d.m.Y H:i') }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Прилет</small>
                                            <strong>{{ $flighthistory->flight->arrival_date->format('d.m.Y H:i') }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Информация -->
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="border-end">
                                            <div class="text-primary fw-bold">
                                                @php
                                                    $duration = $flighthistory->flight->arrival_date->diff($flighthistory->flight->departure_date);
                                                    echo $duration->h . 'ч ' . $duration->i . 'м';
                                                @endphp
                                            </div>
                                            <small class="text-muted">Время в полете</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-success fw-bold">
                                            {{ $flighthistory->flight_hours }}ч
                                        </div>
                                        <small class="text-muted">Налет часов</small>
                                    </div>
                                </div>

                                <!-- Важная информация -->
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock me-2"></i>
                                        <div>
                                            <small class="fw-bold d-block">Необходимо приехать в аэропорт к:</small>
                                            <strong>{{ $flighthistory->flight->departure_date->subHours(3)->format('d.m.Y H:i') }}</strong><br>
                                            <small>Доп. информация в разделе "Мои задачи"</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Создано: {{ $flighthistory->created_at->format('d.m.Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>



        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-plane-slash fa-3x text-muted"></i>
                </div>
                <h5 class="text-muted">Нет данных о истории полетов</h5>
                <p class="text-muted">Здесь будут отображаться ваши назначенные полеты</p>
            </div>
        @endif
    </div>

    <style>
        .card {
            border: 1px solid #e3f2fd;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }
        .badge {
            font-size: 0.7em;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeaa7;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Добавляем интерактивность карточкам
            document.querySelectorAll('.card').forEach(card => {
                card.addEventListener('click', function() {
                    this.classList.toggle('border-primary');
                });
            });
        });
    </script>
@endsection
