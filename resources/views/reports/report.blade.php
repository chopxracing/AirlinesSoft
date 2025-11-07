@extends('layout')

@section('main_content')
    <div class="container-fluid py-4">
        <!-- Заголовок -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">📊 Отчеты</h4>
                        <p class="text-muted mb-0">Экспорт данных в Excel</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary fs-6">Доступно: 3 отчета</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Карточки отчетов -->
        <div class="row g-4">
            <!-- Отчет о сотрудниках -->
            <div class="col-xl-4 col-md-6">
                <div class="card report-card h-100">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <div class="report-icon me-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Сотрудники</h6>
                                <small>Полный список персонала</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="report-info mb-3">
                            <div class="row text-center g-2">
                                <div class="col-6">
                                    <div class="p-2 rounded bg-light">
                                        <div class="text-primary fw-bold">👨‍✈️</div>
                                        <small class="text-muted">Пилоты</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 rounded bg-light">
                                        <div class="text-success fw-bold">👩‍💼</div>
                                        <small class="text-muted">Персонал</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled small mb-3">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Контактные данные
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Должности и роли
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Статистика полетов
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="/reports/usersexport" class="btn btn-primary w-100 btn-download">
                            <i class="fas fa-download me-2"></i>
                            Скачать отчет
                        </a>
                    </div>
                </div>
            </div>

            <!-- Отчет о полетах -->
            <div class="col-xl-4 col-md-6">
                <div class="card report-card h-100">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex align-items-center">
                            <div class="report-icon me-3">
                                <i class="fas fa-plane fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Полеты</h6>
                                <small>История рейсов</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="report-info mb-3">
                            <div class="row text-center g-2">
                                <div class="col-6">
                                    <div class="p-2 rounded bg-light">
                                        <div class="text-success fw-bold">🛫</div>
                                        <small class="text-muted">Вылеты</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 rounded bg-light">
                                        <div class="text-warning fw-bold">🛬</div>
                                        <small class="text-muted">Прилеты</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled small mb-3">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Маршруты и расписание
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Статусы полетов
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Налет часов
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Экипажи
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="/reports/flightsexport" class="btn btn-success w-100 btn-download">
                            <i class="fas fa-download me-2"></i>
                            Скачать отчет
                        </a>
                    </div>
                </div>
            </div>

            <!-- Отчет о самолетах -->
            <div class="col-xl-4 col-md-6">
                <div class="card report-card h-100">
                    <div class="card-header bg-warning text-dark">
                        <div class="d-flex align-items-center">
                            <div class="report-icon me-3">
                                <i class="fas fa-fighter-jet fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Самолеты</h6>
                                <small>Авиационный парк</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="report-info mb-3">
                            <div class="row text-center g-2">
                                <div class="col-6">
                                    <div class="p-2 rounded bg-light">
                                        <div class="text-warning fw-bold">✈️</div>
                                        <small class="text-muted">Типы</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 rounded bg-light">
                                        <div class="text-info fw-bold">🔧</div>
                                        <small class="text-muted">Состояние</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled small mb-3">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Технические характеристики
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                История обслуживания
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Статусы готовности
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                График полетов
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="/reports/aircraftsexport" class="btn btn-warning w-100 btn-download">
                            <i class="fas fa-download me-2"></i>
                            Скачать отчет
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Информация об экспорте -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1"><i class="fas fa-info-circle text-primary me-2"></i>Информация об экспорте</h6>
                                <p class="mb-0 text-muted small">
                                    Все отчеты экспортируются в формате Excel (.xlsx). Для скачивания требуется подключение к интернету.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .report-card {
            border: 1px solid #e3f2fd;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: #2196f3;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }

        .report-icon {
            opacity: 0.9;
        }

        .btn-download {
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-download:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .list-unstyled li {
            padding-left: 0.5rem;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .report-info .rounded {
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .report-info .rounded:hover {
            background-color: #e3f2fd !important;
            border-color: #2196f3;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .card-footer {
            border-top: 1px solid #e3f2fd;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Анимация появления карточек
            const cards = document.querySelectorAll('.report-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });

            // Добавляем подтверждение скачивания
            document.querySelectorAll('.btn-download').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const cardTitle = this.closest('.card').querySelector('.card-header h6').textContent;
                    if (!confirm(`Начать скачивание отчета "${cardTitle.trim()}"?`)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
