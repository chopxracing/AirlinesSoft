<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Вход в систему</title>
    <!-- Bootstrap CSS -->
    @vite(["resources/sass/app.scss", "resources/js/bootstrap.js"])
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px 20px 0 0;
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .login-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 20px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .form-control.with-icon {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #6c757d;
            margin: 2rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }

        .divider span {
            padding: 0 1rem;
            font-size: 0.9rem;
        }

        .additional-links a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .additional-links a:hover {
            color: #764ba2;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        /* Анимации */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card login-card">
                <div class="login-header">
                    <div class="login-icon">
                        <i class="fas fa-plane-departure"></i>
                    </div>
                    <h3 class="mb-0">Alpha airlines</h3>
                    <p class="mb-0 mt-2 opacity-75">Вход в панель управления</p>
                </div>

                <div class="card-body p-4">
                    <!-- Вывод ошибок -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Неверный логин или пароль
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold">Логин</label>
                            <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                <input type="text"
                                       name="username"
                                       id="username"
                                       class="form-control with-icon"
                                       placeholder="Введите ваш логин"
                                       value="{{ old('username') }}"
                                       required
                                       autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Пароль</label>
                            <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control with-icon"
                                       placeholder="Введите ваш пароль"
                                       required>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-login text-white">
                                <i class="fas fa-sign-in-alt me-2"></i>Войти в систему
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center py-3">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Ваши данные защищены
                    </small>
                </div>
            </div>

            <div class="text-center mt-4">
                <small class="text-white opacity-75">
                    &copy; 2024 Авиационная система управления. Все права защищены.
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Добавляем интерактивность
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.form-control');

        inputs.forEach(input => {
            // Эффект при фокусе
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focus');
            });

            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focus');
                }
            });

            // Валидация в реальном времени
            input.addEventListener('input', function() {
                if (this.value) {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Показать/скрыть пароль (можно добавить иконку глаза)
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            const inputGroup = passwordInput.closest('.input-group');
            const toggleButton = document.createElement('button');
            toggleButton.type = 'button';
            toggleButton.className = 'btn btn-outline-secondary';
            toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
            toggleButton.style.borderRadius = '0 10px 10px 0';
            toggleButton.style.border = '2px solid #e9ecef';
            toggleButton.style.borderLeft = 'none';

            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });

            inputGroup.appendChild(toggleButton);
            passwordInput.classList.add('with-icon');
        }
    });
</script>
</body>
</html>
