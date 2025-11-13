@extends('work.worklayout')

@section('main_content')
    <div class="container-fluid p-3">
        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">📋 Мои полеты</h4>
            <span class="badge bg-primary fs-6">Всего: {{ $flighthistories->count() }}</span>
        </div>

        @if($flighthistories->count() > 0)
            <div class="row g-3">
                @foreach($flighthistories as $flighthistory)
                    @if($flighthistory->flight && !in_array($flighthistory->flight->flight_status_id, [4,5]))
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border shadow-sm h-100">
                                <!-- Заголовок рейса -->
                                <div class="card-header bg-light py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold">Рейс #{{ $flighthistory->flight->flight_number }}</h6>
                                        <span class="badge bg-secondary small">{{ $flighthistory->flight->aircraft->name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="card-body p-3">
                                    <!-- Маршрут -->
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="text-center flex-fill">
                                            <div class="fw-bold text-dark">{{ $flighthistory->flight->departure }}</div>
                                        </div>
                                        <div class="px-2">
                                            <i class="fas fa-arrow-right text-muted"></i>
                                        </div>
                                        <div class="text-center flex-fill">
                                            <div class="fw-bold text-dark">{{ $flighthistory->flight->arrival }}</div>
                                        </div>
                                    </div>

                                    <!-- Время полета -->
                                    <div class="bg-light rounded p-2 mb-3">
                                        <div class="row g-2 text-center">
                                            <div class="col-6">
                                                <div class="small text-muted">Вылет</div>
                                                <div class="fw-bold">{{ $flighthistory->flight->departure_date->format('d.m.Y H:i') }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="small text-muted">Прилет</div>
                                                <div class="fw-bold">{{ $flighthistory->flight->arrival_date->format('d.m.Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Статистика -->
                                    <div class="row g-2 text-center mb-3">
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
                                            <small> {{ $flighthistory->flight->airport }}</small>
                                        </div>
                                    </div>

                                    <!-- Погода -->
                                    @if(isset($weatherData[$flighthistory->id]))
                                        <div class="border-top pt-3 mt-3">
                                            <div class="small fw-bold text-muted mb-2">Метеоусловия:</div>
                                            <div class="row g-3">
                                                @php $dep = $weatherData[$flighthistory->id]['departure'] ?? null; @endphp
                                                @php $arr = $weatherData[$flighthistory->id]['arrival'] ?? null; @endphp

                                                @if($dep)
                                                    <div class="col-6">
                                                        <div class="small">
                                                            <div class="fw-bold text-dark mb-1">{{ $flighthistory->flight->departure }}</div>
                                                            <div class="mb-1">🌡 {{ $dep['main']['temp'] }}°C</div>
                                                            <div class="mb-1">☁️ {{ $dep['weather'][0]['description'] }}</div>
                                                            <div class="mb-1">👁️ {{ $dep['visibility'] }} м</div>
                                                            <div class="mb-1">💨 {{ $dep['wind']['speed'] }} м/с, {{ $dep['wind']['deg'] }}°</div>
                                                            <div class="mb-0">📊 {{ $dep['main']['grnd_level'] }} гПа</div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($arr)
                                                    <div class="col-6">
                                                        <div class="small">
                                                            <div class="fw-bold text-dark mb-1">{{ $flighthistory->flight->arrival }}</div>
                                                            <div class="mb-1">🌡 {{ $arr['main']['temp'] }}°C</div>
                                                            <div class="mb-1">☁️ {{ $arr['weather'][0]['description'] }}</div>
                                                            <div class="mb-1">👁️ {{ $arr['visibility'] }} м</div>
                                                            <div class="mb-1">💨 {{ $arr['wind']['speed'] }} м/с, {{ $arr['wind']['deg'] }}°</div>
                                                            <div class="mb-0">📊 {{ $arr['main']['grnd_level'] }} гПа</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Важная информация -->
                                    <div class="alert alert-warning py-2 px-3 mt-3 mb-0">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-clock me-2 mt-1 small"></i>
                                            <div>
                                                <small class="fw-bold d-block">Необходимо приехать в аэропорт к:</small>
                                                <strong>{{ $flighthistory->flight->departure_date->subHours(3)->format('d.m.Y H:i') }}</strong><br>
                                                <small>Доп. информация в разделе "Мои задачи"</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent py-2">
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
                <i class="fas fa-plane-slash fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Нет данных о истории полетов</h5>
                <p class="text-muted">Здесь будут отображаться ваши назначенные полеты</p>
            </div>
        @endif
    </div>

    <style>
        .card {
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            font-size: 0.875rem;
        }
        .badge {
            font-size: 0.75em;
        }
    </style>
@endsection
