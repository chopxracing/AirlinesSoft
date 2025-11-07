@extends('work.worklayout')

@section('main_content')
    <div class="container-fluid">
        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">🛫 Мои рейсы</h4>
                <p class="text-muted mb-0">Управление статусами полетов</p>
            </div>
            <span class="badge bg-primary fs-6">Рейсов: {{ $flighthistories->count() }}</span>
        </div>

        @if($flighthistories->count() > 0)
            <div class="row g-4">
                @foreach($flighthistories as $flighthistory)
                    @if($flighthistory->flight && !in_array($flighthistory->flight->flight_status_id, [4, 5]))
                    <div class="col-lg-6">
                        <div class="card flight-card h-100">
                            <!-- Заголовок карточки -->
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-primary">
                                        ✈️ Рейс #{{ $flighthistory->flight->flight_number }}
                                    </h6>

                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Маршрут -->
                                <div class="flight-route mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="text-center">
                                            <div class="airport-code bg-primary text-white rounded-circle mx-auto mb-2">
                                                {{ substr($flighthistory->flight->departure, 0, 3) }}
                                            </div>
                                            <strong class="d-block">{{ $flighthistory->flight->departure }}</strong>
                                            <small class="text-muted">Вылет</small>
                                        </div>
                                        <div class="mx-3 position-relative">
                                            <div class="flight-line"></div>
                                            <i class="fas fa-plane text-primary position-relative bg-white ps-2"></i>
                                        </div>
                                        <div class="text-center">
                                            <div class="airport-code bg-success text-white rounded-circle mx-auto mb-2">
                                                {{ substr($flighthistory->flight->arrival, 0, 3) }}
                                            </div>
                                            <strong class="d-block">{{ $flighthistory->flight->arrival }}</strong>
                                            <small class="text-muted">Прилет</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Время полета -->
                                <div class="flight-times mb-3">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="time-card p-2 rounded text-center bg-light">
                                                <small class="text-muted d-block">Вылет</small>
                                                <strong>{{ $flighthistory->flight->departure_date->format('d.m.Y H:i') }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="time-card p-2 rounded text-center bg-light">
                                                <small class="text-muted d-block">Прилет</small>
                                                <strong>{{ $flighthistory->flight->arrival_date->format('d.m.Y H:i') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Статус и информация -->
                                <div class="flight-info mb-4">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="info-item p-2 rounded text-center">
                                                <div class="status-badge
                                            @switch($flighthistory->flight->flight_status_id)
                                                @case(1) bg-secondary @break
                                                @case(2) bg-info @break
                                                @case(3) bg-success @break
                                                @case(4) bg-warning @break
                                                @case(5) bg-danger @break
                                                @default bg-light text-dark
                                            @endswitch
                                            rounded px-2 py-1">
                                                    <strong>
                                                        {{ $flighthistory->flight->flightStatus->name ?? 'Статус: ' . $flighthistory->flight->flight_status_id }}
                                                    </strong>
                                                </div>
                                                <small class="text-muted">Текущий статус</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item p-2 rounded text-center">
                                                <div class="text-primary fw-bold">
                                                    {{ $flighthistory->flight->updated_at->addHours(3)->format('H:i') }}
                                                </div>
                                                <small class="text-muted">Обновлено</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Кнопки управления статусом -->
                                <form method="POST" action="{{ route('work.flightstatusupdate') }}" class="flight-actions">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="flight_id" value="{{ $flighthistory->flight->id }}">

                                    <div class="row g-2">
                                        <div class="col-6">
                                            <button type="submit" name="flight_status_id" value="2"
                                                    class="btn btn-outline-info w-100 btn-action">
                                                <i class="fas fa-tools me-1"></i>
                                                Подготовка
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button type="submit" name="flight_status_id" value="3"
                                                    class="btn btn-outline-success w-100 btn-action">
                                                <i class="fas fa-truck-moving me-1"></i>
                                                Буксировка
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button type="submit" name="flight_status_id" value="4"
                                                    class="btn btn-outline-warning w-100 btn-action">
                                                <i class="fas fa-plane-arrival me-1"></i>
                                                Приземлились
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button type="submit" name="flight_status_id" value="5"
                                                    class="btn btn-outline-danger w-100 btn-action">
                                                <i class="fas fa-ban me-1"></i>
                                                Отмена
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Футер карточки -->
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $flighthistory->flight->updated_at->addHours(3)->format('d.m.Y H:i') }}
                                    </small>
                                    <small class="text-muted">
                                        ID: {{ $flighthistory->flight->id }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @else
            <!-- Пустое состояние -->
            <div class="text-center py-5">
                <div class="empty-state mb-4">
                    <i class="fas fa-plane-slash fa-4x text-muted mb-3"></i>
                </div>
                <h5 class="text-muted mb-3">Нет активных рейсов</h5>
                <p class="text-muted">Здесь будут отображаться ваши назначенные полеты</p>
            </div>
        @endif
    </div>

    <style>
        .flight-card {
            border: 1px solid #e3f2fd;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        }

        .flight-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            border-color: #2196f3;
        }

        .airport-code {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
        }

        .flight-line {
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, #2196f3 0%, #4caf50 100%);
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
        }

        .flight-line::before {
            content: '';
            position: absolute;
            width: 6px;
            height: 6px;
            background: #2196f3;
            border-radius: 50%;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
        }

        .flight-line::after {
            content: '';
            position: absolute;
            width: 6px;
            height: 6px;
            background: #4caf50;
            border-radius: 50%;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
        }

        .time-card {
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .time-card:hover {
            background-color: #e3f2fd !important;
            border-color: #2196f3;
        }

        .btn-action {
            transition: all 0.3s ease;
            border-width: 2px;
            font-weight: 500;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        .status-badge {
            font-size: 0.8rem;
        }

        .info-item {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }

        .flight-actions .btn {
            padding: 8px 12px;
            font-size: 0.85rem;
        }

        .card-header {
            border-bottom: 2px solid #e3f2fd;
        }

        .card-footer {
            border-top: 1px solid #e3f2fd;
            font-size: 0.8rem;
        }

        .empty-state {
            opacity: 0.6;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Анимация появления карточек
            const cards = document.querySelectorAll('.flight-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Подтверждение критических действий
            document.querySelectorAll('button[value="5"]').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Вы уверены, что хотите отменить полет? Это действие нельзя отменить.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
