<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UMKM Manager</title>
    <style>
        :root {
            --peach: #FFCDB2;
            --mocha-cream: #E8CEBF;
            --sage-green: #B7C4A4;
            --warm-white: #F8F4F0;
            --charcoal-gray: #3A3A3A;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--warm-white) 0%, var(--mocha-cream) 100%);
            color: var(--charcoal-gray);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: var(--warm-white);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-left {
            background: linear-gradient(135deg, var(--peach), var(--sage-green));
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .brand h1 {
            font-size: 2.5rem;
            color: var(--charcoal-gray);
            margin-bottom: 15px;
        }

        .brand p {
            color: var(--charcoal-gray);
            opacity: 0.8;
            font-size: 1rem;
            line-height: 1.6;
        }

        .login-right {
            padding: 60px 50px;
        }

        .login-right h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            color: var(--charcoal-gray);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--charcoal-gray);
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--mocha-cream);
            border-radius: 12px;
            background: var(--warm-white);
            font-size: 1rem;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--sage-green);
            box-shadow: 0 0 0 3px rgba(183, 196, 164, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 50px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--charcoal-gray);
            opacity: 0.6;
            transition: opacity 0.3s;
            background: none;
            border: none;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            opacity: 1;
        }

        .toggle-password svg {
            width: 22px;
            height: 22px;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--sage-green), var(--peach));
            border: none;
            border-radius: 12px;
            font-weight: 600;
            color: var(--charcoal-gray);
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(183, 196, 164, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Custom Alert Modal */
        .alert-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .alert-overlay.show {
            display: flex;
        }

        .alert-box {
            background: white;
            border-radius: 16px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
            text-align: center;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background: #ffebee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-icon svg {
            width: 35px;
            height: 35px;
            color: #c62828;
        }

        .alert-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--charcoal-gray);
            margin-bottom: 10px;
        }

        .alert-message {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .alert-btn {
            background: linear-gradient(135deg, var(--sage-green), var(--peach));
            border: none;
            border-radius: 10px;
            padding: 12px 40px;
            font-weight: 600;
            color: var(--charcoal-gray);
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
        }

        .alert-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(183, 196, 164, 0.4);
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .login-left {
                padding: 40px 30px;
            }

            .login-right {
                padding: 40px 30px;
            }

            .brand h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="brand">
                <h1>SAVKA.CO Management</h1>
                <p>Kelola bisnis Anda dengan efisien. Sistem untuk Owner, Produksi, dan Distribusi.</p>
            </div>
        </div>

        <div class="login-right">
            <h2>Login</h2>

            <form action="{{ route('login') }}" method="POST" autocomplete="off" id="loginForm">
                @csrf
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" placeholder="Masukkan username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Masukkan password" required autocomplete="off">
                        <button type="button" class="toggle-password" id="togglePassword">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">Masuk</button>
            </form>
        </div>
    </div>

    <!-- Custom Alert Modal -->
    <div class="alert-overlay" id="alertOverlay">
        <div class="alert-box">
            <div class="alert-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="alert-title">Login Gagal</div>
            <div class="alert-message" id="alertMessage">Username atau password yang Anda masukkan salah. Silakan coba lagi.</div>
            <button class="alert-btn" onclick="closeAlert()">OK</button>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // Change icon
            if (type === 'text') {
                // Eye slash icon (closed eye)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                // Eye icon (open eye)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        });

        // Show alert if there's an error from Laravel
        window.addEventListener('DOMContentLoaded', function() {
            // Check for Laravel validation errors
            @if($errors->has('login_error'))
                showAlert('{{ $errors->first("login_error") }}');
            @elseif($errors->any())
                showAlert('{{ $errors->first() }}');
            @endif

            // Check for session flash messages
            @if(session('error'))
                showAlert('{{ session("error") }}');
            @endif

            @if(session('success'))
                showSuccessAlert('{{ session("success") }}');
            @endif

            @if(session('message'))
                showAlert('{{ session("message") }}');
            @endif
        });

        function showAlert(message) {
            const alertOverlay = document.getElementById('alertOverlay');
            const alertMessage = document.getElementById('alertMessage');
            const alertTitle = document.querySelector('.alert-title');
            const alertIcon = document.querySelector('.alert-icon');

            alertMessage.textContent = message;
            alertTitle.textContent = 'Login Gagal';
            alertIcon.style.background = '#ffebee';
            alertIcon.querySelector('svg').style.color = '#c62828';
            alertOverlay.classList.add('show');

            // Debug log
            console.log('Alert shown with message:', message);
        }

        function showSuccessAlert(message) {
            const alertOverlay = document.getElementById('alertOverlay');
            const alertMessage = document.getElementById('alertMessage');
            const alertTitle = document.querySelector('.alert-title');
            const alertIcon = document.querySelector('.alert-icon');

            alertMessage.textContent = message;
            alertTitle.textContent = 'Berhasil';
            alertIcon.style.background = '#e8f5e9';
            alertIcon.querySelector('svg').innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            `;
            alertIcon.querySelector('svg').style.color = '#2e7d32';
            alertOverlay.classList.add('show');
        }

        function closeAlert() {
            const alertOverlay = document.getElementById('alertOverlay');
            alertOverlay.classList.remove('show');
        }

        // Close alert when clicking outside the box
        document.getElementById('alertOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAlert();
            }
        });

        // Close alert with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAlert();
            }
        });
    </script>
</body>
</html>
